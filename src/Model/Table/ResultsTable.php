<?php

namespace App\Model\Table;

use App\Model\Entity\Result;

use Cake\Database\Schema\Table as Schema;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ResultsTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'belongsTo' => [
                'Clubs',
                'PlayerAs' => [
                    'className' => 'Players',
                    'foreignKey' => 'player_a_id',
                    'propertyName' => 'player_a'
                ],
                'PlayerBs' => [
                    'className' => 'Players',
                    'foreignKey' => 'player_b_id',
                    'propertyName' => 'player_b'
                ]
            ],
            'hasOne' => [
                'Disputes'
            ]
        ]);

        $this->addBehavior('Timestamp');
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('player_b_id', 'create')
            ->notEmpty('player_b_id');

        $validator
            ->requirePresence('player_a_score', 'create')
            ->notEmpty('player_a_score')
            ->nonNegativeInteger('player_a_score');

        $validator
            ->requirePresence('player_b_score', 'create')
            ->notEmpty('player_b_score')
            ->nonNegativeInteger('player_b_score');

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['club_id'], 'Clubs'));
        $rules->add($rules->existsIn(['player_a_id'], 'PlayerAs'));
        $rules->add($rules->existsIn(['player_b_id'], 'PlayerBs'));

        return $rules;
    }

    /**
     * @return void
     */
    public function beforeSave(Event $event, Result $result)
    {
        if ($result->player_a_id === $result->player_b_id) {
            $result->errors('player_b_id', [
                'invalid' => 'You cannot add results against yourself'
            ]);

            return false;
        }

        if (!$this->Clubs->hasMember($result->club_id, $result->player_b_id, 'id')) {
            $result->errors('player_b_id', [
                'invalid' => 'You can only add results against members of this club'
            ]);

            return false;
        }

        if (!$result->is_deleted) {
            $snapshots = $this->Clubs->Players->snapshots($result);

            $result->set('player_a_snapshot', $snapshots['a']);
            $result->set('player_b_snapshot', $snapshots['b']);
        }
    }

    /**
     * @return \Cake\ORM\Query
     */
    public function findTree(Query $query, array $options)
    {
        $query
            ->where([
                'Results.id IN' => $this->idTree($options['result'])
            ])
            ->order([
                'Results.created' => 'ASC'
            ]);

        return $query;
    }

    /**
     * @return array
     */
    public function idTree(Result $result = null)
    {
        if (!$result) {
            return [];
        }

        $playerIds = [
            $result->player_a_id,
            $result->player_b_id
        ];

        $where = [
            'id !=' => $result->id,
            'club_id' => $result->club_id,
            'is_deleted' => false,
            'created >=' => $result->created
        ];

        $left = $this
            ->find()
            ->where($where + ['player_a_id IN' => $playerIds])
            ->first();

        $right = $this
            ->find()
            ->where($where + ['player_b_id IN' => $playerIds])
            ->first();

        return [$result->id => $result->id] + $this->idTree($left) + $this->idTree($right);
    }

    /**
     * @return bool
     */
    public function isAgainst($id, $userId)
    {
        return !$this
            ->findById($id)
            ->innerJoinWith('PlayerBs', function ($q) use ($userId) {
                $q->where(['PlayerBs.user_id' => $userId]);

                return $q;
            })
            ->isEmpty();
    }

    /**
     * @return bool
     */
    public function isDisputed($id)
    {
        return $this->Disputes->exists([
            'result_id' => $id
        ]);
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
     * @return void
     */
    public function saveTree(Result $result)
    {
        $this->connection()->transactional(function () use ($result) {
            // Find tree of affected results
            $results = $this->find('tree', [
                'result' => $result
            ]);

            // Revert all players in result tree and resave results
            $revertedPlayers = [];
            foreach ($results as $result) {
                foreach (['player_a', 'player_b'] as $player) {
                    $playerId = $result->{$player . '_id'};

                    if (!isset($revertedPlayers[$playerId])) {
                        $revertedPlayers[$playerId] = $this->Clubs->Players->revert($result, $player);
                    }
                }

                $result->dirty('modified', true);

                $this->save($result);
            }
        });
    }

    /**
     * @return void
     */
    public function softDelete(Result $result)
    {
        $this->connection()->transactional(function () use ($result) {
            $result->set('is_deleted', true);

            $this->save($result);
            $this->saveTree($result);
        });
    }

    /**
     * @return bool
     */
    public function wasCreatedBy($id, $userId)
    {
        return !$this
            ->findById($id)
            ->innerJoinWith('PlayerAs', function ($q) use ($userId) {
                $q->where(['PlayerAs.user_id' => $userId]);

                return $q;
            })
            ->isEmpty();
    }

    /**
     * @return void
     */
    public function wasWithinLast($id, $period)
    {
        return $this->exists([
            'id' => $id,
            'created >=' => new Time('-' . $period)
        ]);
    }

    /**
     * @return \Cake\Database\Schema\Table
     */
    protected function _initializeSchema(Schema $schema)
    {
        $schema->columnType('player_a_snapshot', 'json');
        $schema->columnType('player_b_snapshot', 'json');

        return $schema;
    }
}
