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
            ->notEmpty('player_a_score');

        $validator
            ->requirePresence('player_b_score', 'create')
            ->notEmpty('player_b_score');

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
            return false;
        }

        $snapshots = $this->Clubs->Players->snapshots($result);

        $result->set('player_a_snapshot', $snapshots['a']);
        $result->set('player_b_snapshot', $snapshots['b']);
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
    public function isDisputed($id)
    {
        return $this->Disputes->exists([
            'result_id' => $id
        ]);
    }

    /**
     * @return bool
     */
    public function isOwnedBy($id, $playerAId)
    {
        return $this->exists([
            'id' => $id,
            'player_a_id' => $playerAId
        ]);
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
