<?php
namespace App\Model\Table;

use App\Model\Entity\Challenge;

use ArrayObject;

use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ChallengesTable extends Table
{
    use MailerAwareTrait;

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->addAssociations([
            'belongsTo' => [
                'Clubs',
                'Matches',
                'PlayerAs' => ['className' => 'Players'],
                'PlayerBs' => ['className' => 'Players']
            ]
        ]);

        $this->addBehavior('Timestamp');
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationAdd(Validator $validator)
    {
        $validator
            ->requirePresence('location')
            ->notEmpty('location');

        $validator
            ->requirePresence('match_datetime')
            ->dateTime('match_datetime')
            ->notEmpty('match_datetime');

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['club_id'], 'Clubs'));
        $rules->add($rules->existsIn(['match_id'], 'Matches'));
        $rules->add($rules->existsIn(['player_a_id'], 'PlayerAs'));
        $rules->add($rules->existsIn(['player_b_id'], 'PlayerBs'));

        return $rules;
    }

    /**
     * @return void
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        if (isset($data['match_datetime']) &&
            is_string($data['match_datetime'])
        ) {
            $data['match_datetime'] = Time::parseDateTime(
                $data['match_datetime'],
                "yyyy-MM-dd'T'HH:mm" // ICU compatible format
            );
        }
    }

    /**
     * @return void
     */
    public function patchEntityAdd(Challenge $challenge, array $data, $clubId, $playerId)
    {
        $this->patchEntity($challenge, $data, ['validate' => 'add']);

        if ($challenge->match_datetime &&
            $challenge->match_datetime < Time::now()
        ) {
            $challenge->setError('match_datetime', [
                'invalid' => 'The date and time must be in the future'
            ]);
        }

        if (!empty($challenge->getErrors())) {
            return;
        }

        $challenge->set('club_id', $clubId);
        $challenge->set('player_a_id', $playerId);
    }

    /**
     * @return bool
     */
    public function accept(Challenge $challenge, $playerId)
    {
        if ($challenge->player_b_id ||
            $challenge->player_a_id === $playerId ||
            $challenge->match_datetime < Time::now()
        ) {
            return false;
        }

        $challenge->set('player_b_id', $playerId);

        $this->save($challenge);

        $this->getMailer('Challenge')->send(
            'playerAccepted',
            [$challenge]
        );

        return true;
    }

    /**
     * @return bool
     */
    public function softDelete(Challenge $challenge, $playerId)
    {
        if ($challenge->player_a_id !== $playerId) {
            return false;
        }

        $this->loadInto($challenge, ['PlayerAs']);

        $this->connection()->transactional(function () use ($challenge) {
            if ($challenge->player_b_id &&
               ($challenge->match_datetime < Time::now() || $challenge->match_datetime->isWithinNext('24 hours'))
            ) {
                $this->PlayerAs->Users->updateReputation($challenge->player_a->user_id, -10);
            }

            $challenge->set('deleted', Time::now());

            $this->save($challenge);
        });

        if ($challenge->player_b_id) {
            $this->getMailer('Challenge')->send(
                'playerDeleted',
                [$challenge]
            );
        }

        return true;
    }

    /**
     * @return bool
     */
    public function report(Challenge $challenge, $playerId)
    {
        if (!$challenge->player_b_id ||
           ($challenge->player_a_id !== $playerId && $challenge->player_b_id !== $playerId) ||
           $challenge->match_datetime > Time::now()
        ) {
            return false;
        }

        $otherPlayer = $challenge->player_b_id === $playerId
            ? ['table' => 'PlayerAs', 'property' => 'player_a']
            : ['table' => 'PlayerBs', 'property' => 'player_b'];

        $this->loadInto($challenge, [$otherPlayer['table']]);

        $this->connection()->transactional(function () use ($challenge, $otherPlayer) {
            $this->{$otherPlayer['table']}->Users->updateReputation(
                $challenge->{$otherPlayer['property']}->user_id,
                -10
            );

            $challenge->set('deleted', Time::now());

            $this->save($challenge);
        });

        return true;
    }

    /**
     * @return bool
     */
    public function withdraw(Challenge $challenge, $playerId)
    {
        if ($challenge->player_b_id !== $playerId) {
            return false;
        }

        $this->loadInto($challenge, ['PlayerBs']);

        // Save playerB for later!
        $playerB = $challenge->player_b;

        $this->connection()->transactional(function () use ($challenge) {
            if ($challenge->match_datetime < Time::now() ||
                $challenge->match_datetime->isWithinNext('24 hours')
            ) {
                $this->PlayerBs->Users->updateReputation($challenge->player_b->user_id, -10);
            }

            $challenge->set('player_b_id', null);

            $this->save($challenge);
        });

        $this->getMailer('Challenge')->send(
            'playerWithdrew',
            [$challenge, $playerB]
        );

        return true;
    }

    /**
     * @return void
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        if (!isset($options['ignoreBeforeFind'])) {
            $query
                ->where([
                    'OR' => [
                        $this->aliasField('player_b_id') . ' IS NOT' => null,
                        $this->aliasField('match_datetime') . ' >' => Time::now()
                    ],
                    $this->aliasField('match_id') . ' IS' => null,
                    $this->aliasField('deleted') . ' IS' => null
                ])
                ->orderAsc($this->aliasField('match_datetime'));
        }
    }

    /**
     * @return \Cake\ORM\Query
     */
    public function findByPlayerId(Query $query, array $options)
    {
        if (!isset($options['player_id']) ||
            $options['player_id'] === 'all'
        ) {
            $query->where([
                $this->aliasField('match_datetime') . ' >' => Time::now()
            ]);

            return $query;
        }

        $query->where([
            'OR' => [
                $this->aliasField('player_a_id') => $options['player_id'],
                $this->aliasField('player_b_id') => $options['player_id']
            ]
        ]);

        return $query;
    }

    /**
     * @return \Cake\ORM\Query
     */
    public function findFiltered(Query $query, array $options)
    {
        if (!isset($options['filter']) ||
            !in_array($options['filter'], ['all', 'open', 'accepted']) ||
            $options['filter'] === 'all'
        ) {
            return $query;
        }

        $query->where([
            $this->aliasField('player_b_id') . ' IS' . ($options['filter'] === 'accepted' ? ' NOT' : '') => null
        ]);

        return $query;
    }

    /**
     * @return \Cake\ORM\Query
     */
    public function findPopulated(Query $query, array $options)
    {
        $query->contain([
            'PlayerAs.Users',
            'PlayerBs.Users'
        ]);

        return $query;
    }

    /**
     * @return bool
     */
    public function isOwnedBy($id, $clubId)
    {
        return $this->exists([
            'id' => $id,
            'club_id' => $clubId
        ]);
    }

    /**
     * @return bool
     */
    public function exists($conditions)
    {
        return (bool)count(
            $this
                ->find('all', ['ignoreBeforeFind' => true])
                ->select(['existing' => 1])
                ->where($conditions)
                ->limit(1)
                ->enableHydration(false)
                ->toArray()
        );
    }
}
