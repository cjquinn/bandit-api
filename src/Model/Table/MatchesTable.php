<?php

namespace App\Model\Table;

use App\Model\Entity\Match;

use Cake\Database\Schema\Table as Schema;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class MatchesTable extends Table
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
    public function beforeSave(Event $event, Match $match)
    {
        if ($match->player_a_id === $match->player_b_id) {
            $match->errors('player_b_id', [
                'invalid' => 'You cannot add matches against yourself'
            ]);

            return false;
        }

        if (!$this->Clubs->hasMember($match->club_id, $match->player_b_id, 'id')) {
            $match->errors('player_b_id', [
                'invalid' => 'You can only add matches against members of this club'
            ]);

            return false;
        }

        if (!$match->deleted) {
            $snapshots = $this->Clubs->Players->snapshots($match);

            $match->set('player_a_snapshot', $snapshots['a']);
            $match->set('player_b_snapshot', $snapshots['b']);
        }
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
     * @return \Cake\ORM\Query
     */
    public function findTree(Query $query, array $options)
    {
        $query
            ->where([
                'Matches.id IN' => $this->idTree($options['match'])
            ])
            ->order([
                'Matches.created' => 'ASC'
            ]);

        return $query;
    }

    /**
     * @return array
     */
    public function idTree(Match $match = null)
    {
        if (!$match) {
            return [];
        }

        $playerIds = [
            $match->player_a_id,
            $match->player_b_id
        ];

        $where = [
            'id !=' => $match->id,
            'club_id' => $match->club_id,
            'deleted IS' => null,
            'created >=' => $match->created
        ];

        $left = $this
            ->find()
            ->where($where + ['player_a_id IN' => $playerIds])
            ->first();

        $right = $this
            ->find()
            ->where($where + ['player_b_id IN' => $playerIds])
            ->first();

        return [$match->id => $match->id] + $this->idTree($left) + $this->idTree($right);
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
            'match_id' => $id
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
    public function saveTree(Match $match)
    {
        $this->connection()->transactional(function () use ($match) {
            // Find tree of affected matches
            $matches = $this->find('tree', [
                'match' => $match
            ]);

            // Revert all players in match tree and resave matches
            $revertedPlayers = [];
            foreach ($matches as $match) {
                foreach (['player_a', 'player_b'] as $player) {
                    $playerId = $match->{$player . '_id'};

                    if (!isset($revertedPlayers[$playerId])) {
                        $revertedPlayers[$playerId] = $this->Clubs->Players->revert($match, $player);
                    }
                }

                $match->dirty('modified', true);

                $this->save($match);
            }
        });
    }

    /**
     * @return void
     */
    public function softDelete(Match $match)
    {
        $this->connection()->transactional(function () use ($match) {
            $match->set('deleted', new Time());

            $this->save($match);
            $this->saveTree($match);
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
        $schema->setColumnType('player_a_snapshot', 'json');
        $schema->setColumnType('player_b_snapshot', 'json');

        return $schema;
    }
}
