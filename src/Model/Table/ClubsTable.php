<?php

namespace App\Model\Table;

use App\Model\Entity\Club;

use ArrayObject;

use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Exception;

class ClubsTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'belongsTo' => [
                'Founders' => ['className' => 'Users']
            ],
            'hasMany' => [
                'Players',
                'Matches'
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
            ->requirePresence('name')
            ->notEmpty('name');

        $validator
            ->requirePresence('founder', function ($context) {
                return !isset($context['data']['founder_id']);
            })
            ->notEmpty('founder');

        $validator
            ->requirePresence('founder_id', function ($context) {
                return !isset($context['data']['founder']);
            })
            ->notEmpty('founder_id');

        return $validator;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationEdit(Validator $validator)
    {
        $validator
            ->requirePresence('name')
            ->notEmpty('name');

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['founder_id'], 'Founders'));

        return $rules;
    }

    /**
     * @return void
     */
    public function patchEntityAdd(Club $club, array $data, $user)
    {
        if (is_array($user)) {
            $data['founder_id'] = $user['id'];
        }

        $this->patchEntity($club, $data, [
            'associated' => [
                'Founders' => ['validate' => 'add']
            ],
            'fieldList' => [
                'name',
                $user ? 'founder_id' : 'founder'
            ],
            'validate' => 'add'
        ]);
    }

    /**
     * @return void
     */
    public function patchEntityEdit(Club $club, array $data)
    {
        $this->patchEntity($club, $data, ['validate' => 'edit']);
    }

    /**
     * @return void
     */
    public function afterSave(Event $event, Club $club)
    {
        if ($club->isNew()) {
            // Need to create player afterwards as club_id is required
            $player = $this->Players->newEntity();

            $player->set('club_id', $club->id);
            $player->set('user_id', $club->founder_id);

            $this->Players->save($player);

            $club->set('founder', $this->Founders->get($club->founder_id, ['contain' => ['Players']]));
        }
    }

    /**
     * @return int
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function getPlayerId($id, $userId)
    {
        return $this->Players
            ->find()
            ->select(['id'])
            ->where([
                'club_id' => $id,
                'user_id' => $userId
            ])
            ->enableHydration(false)
            ->firstOrFail()['id'];
    }

    /**
     * @return \Cake\ORM\Query
     */
    public function findBanditId(Query $query, array $options)
    {
        $banditId = $this->Players
            ->find('allTimeLeaderboard')
            ->select('id')
            ->innerJoinWith('Users', function ($q) {
                $q->find('auth');

                return $q;
            })
            ->where(['Players.club_id = Clubs.id'])
            ->limit(1);

        $query
            ->select(['bandit_id' => $banditId])
            ->enableAutoFields(true);

        return $query;
    }

    /**
     * @return \Cake\ORM\Query
     * @throws \Exception
     */
    public function findByUserId(Query $query, array $options)
    {
        if (!isset($options['userId'])) {
            throw Exception('Missing userId key in options');
        }

        $lastPlayed = $this->Players->Snapshots
            ->find()
            ->select('created')
            ->where(['player_id = Players.id'])
            ->orderDesc('created')
            ->limit(1);

        $query
            ->select([
                'player_count' => $query->func()->count('Players.id'),
                'last_played' => $lastPlayed
            ])
            ->join([
                'ClubPlayers' => [
                    'table' => 'players',
                    'type' => 'LEFT',
                    'conditions' => 'ClubPlayers.club_id = Clubs.id'
                ],
                'Users' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => [
                        'Users.id = ClubPlayers.user_id',
                        'Users.password IS NOT' => null
                    ]
                ]
            ])
            ->innerJoinWith('Players', function ($q) use ($options) {
                $q->where(['Players.user_id' => $options['userId']]);

                return $q;
            })
            ->group(['Clubs.id', 'last_played'])
            ->enableAutoFields(true);

        return $query;
    }

    /**
     * @return bool
     */
    public function hasDisputingMember($id, $userId)
    {
        return (bool)count(
            $this->Players
                ->find()
                ->where([
                    'Players.club_id' => $id,
                    'Players.user_id' => $userId
                ])
                ->join([
                    'Matches' => [
                        'table' => 'matches',
                        'type' => 'INNER',
                        'conditions' => [
                            // Just the player who added the match...
                            // Because you shouldn't be stopped adding more
                            // matches if you are the one that created
                            // the dispute and only the player that
                            // didn't add the match can open a dispute...
                            'Players.id = Matches.player_a_id'
                        ]
                    ],
                    'Disputes' => [
                        'table' => 'disputes',
                        'type' => 'INNER',
                        'conditions' => [
                            'Matches.id = Disputes.match_id',
                            'Disputes.is_resolved IS' => null
                        ]
                    ]
                ])
                ->limit(1)
                ->enableHydration(false)
                ->toArray()
        );
    }

    /**
     * @return bool
     */
    public function hasMember($id, $memberId, $memberKey = 'user_id')
    {
        return $this->Players->exists([
            'club_id' => $id,
            $memberKey => $memberId
        ]);
    }

    /**
     * @return bool
     */
    public function isOwnedBy($id, $userId)
    {
        return $this->exists([
            'id' => $id,
            'founder_id' => $userId
        ]);
    }
}
