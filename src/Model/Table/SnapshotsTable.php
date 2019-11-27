<?php

namespace App\Model\Table;

use Cake\Core\Configure;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;

class SnapshotsTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setPrimaryKey(['match_id', 'player_id']);

        $this->addAssociations([
            'belongsTo' => ['Matches', 'Players']
        ]);

        $this->addBehavior('Timestamp');
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['match_id'], 'Matches'));
        $rules->add($rules->existsIn(['player_id'], 'Players'));

        return $rules;
    }

    /**
     * @return array
     */
    public function getDailySnapshot($playerId, $date)
    {
        $snapshot = $this
            ->findByPlayerId($playerId)
            ->innerJoinWith('Matches', function ($q) {
                $q->where(['Matches.deleted IS' => null]);

                return $q;
            })
            ->where([$this->aliasField('created') . ' <' => $date])
            ->order([
                $this->aliasField('created') => 'DESC',
                $this->aliasField('match_id') => 'DESC'
            ])
            ->first();

        return $snapshot
            ? $snapshot->stats
            : [
                'rating' => Configure::read('Bandit.initialRating'),
                'difference' => 0,
                'losses' => 0,
                'wins' => 0
            ];
    }
}
