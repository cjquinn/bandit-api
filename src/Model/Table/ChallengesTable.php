<?php
namespace App\Model\Table;

use ArrayObject;

use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ChallengesTable extends Table
{

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
            ->requirePresence('player_b_id', 'create')
            ->notEmpty('player_b_id');

        $validator
            ->requirePresence('location', 'create')
            ->notEmpty('location');

        $validator
            ->dateTime('match_datetime')
            ->requirePresence('match_datetime', 'create')
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
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        if (!isset($options['ignoreBeforeFind'])) {
            $query->where([$this->aliasField('deleted') . ' IS' => null]);
        }
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
    public function wasAcceptedBy($id, $userId)
    {
        return (bool)count(
            $this
                ->findById($id)
                ->innerJoinWith('PlayerBs', function ($q) use ($userId) {
                    $q->where(['PlayerBs.user_id' => $userId]);

                    return $q;
                })
                ->limit(1)
                ->enableHydration(false)
                ->toArray()
        );
    }

    /**
     * @return bool
     */
    public function wasCreatedBy($id, $userId)
    {
        return (bool)count(
            $this
                ->findById($id)
                ->innerJoinWith('PlayerAs', function ($q) use ($userId) {
                    $q->where(['PlayerAs.user_id' => $userId]);

                    return $q;
                })
                ->limit(1)
                ->enableHydration(false)
                ->toArray()
        );
    }
}
