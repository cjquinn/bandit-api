<?php

namespace App\Model\Table;

use App\Model\Entity\Club;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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
                'Results'
            ]
        ]);

        $this->addBehavior('Timestamp');
    }

    /**
     * @return void
     */
    public function patchEntityExistingUser(Club $club, array $data)
    {
        $this->patchEntity($club, $data, [
            'fieldList' => [
                'name',
                'founder_id'
            ],
            'validate' => 'existingUser'
        ]);
    }

    /**
     * @return void
     */
    public function patchEntityNewUser(Club $club, array $data)
    {
        $this->patchEntity($club, $data, [
            'associated' => ['Founders'],
            'fieldList' => [
                'name',
                'founder'
            ],
            'validate' => 'newUser'
        ]);
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        return $validator;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationExistingUser(Validator $validator)
    {
        $validator = $this->validationDefault($validator);

        $validator
            ->requirePresence('founder_id')
            ->notEmpty('founder_id');

        return $validator;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationNewUser(Validator $validator)
    {
        $validator = $this->validationDefault($validator);

        $validator
            ->requirePresence('founder')
            ->notEmpty('founder');

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
    public function afterSave(Event $event, Club $club)
    {
        if ($club->isNew()) {
            $player = $this->Players->newEntity();

            $player->set('club_id', $club->id);
            $player->set('user_id', $club->founder_id);

            $this->Players->save($player);
        }
    }

    /**
     * @return array
     */
    public function dailySnapshot($id, $playerId, $date)
    {
        $result = $this->Results
            ->findByClubId($id)
            ->where([
                'OR' => [
                    ['player_a_id' => $playerId],
                    ['player_b_id' => $playerId]
                ],
                'is_deleted' => false,
                'created <' => $date
            ])
            ->order(['created' => 'DESC'])
            ->first();

        if ($result) {
            return $result->player_a_id === $playerId
                ? $result->player_a_snapshot
                : $result->player_b_snapshot;
        }

        return [
            'rating' => Configure::read('Bandit.initialRating'),
            'difference' => 0,
            'losses' => 0,
            'wins' => 0
        ];
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
            ->hydrate(false)
            ->firstOrFail()['id'];
    }

    /**
     * @return bool
     */
    public function hasDisputingMember($id, $userId)
    {
        return !$this->Players
            ->find()
            ->where([
                'Players.club_id' => $id,
                'Players.user_id' => $userId
            ])
            ->join([
                'Results' => [
                    'table' => 'results',
                    'type' => 'INNER',
                    'conditions' => [
                        'OR' => [
                            'Players.id = Results.player_a_id',
                            'Players.id = Results.player_b_id'
                        ]
                    ]
                ],
                'Disputes' => [
                    'table' => 'disputes',
                    'type' => 'INNER',
                    'conditions' => 'Results.id = Disputes.result_id'
                ]
            ])
            ->isEmpty();
    }

    /**
     * @return bool
     */
    public function hasMember($id, $userId)
    {
        return $this->Players->exists([
            'club_id' => $id,
            'user_id' => $userId,
            'is_member' => true
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
