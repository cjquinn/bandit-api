<?php

namespace App\Model\Table;

use App\Model\Entity\Dispute;

use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class DisputesTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'belongsTo' => [
                'Matches'
            ]
        ]);

        $this->primaryKey('match_id');

        $this->addBehavior('Timestamp');
    }

    /**
     * @return void
     */
    public function patchEntityAdd(Dispute $dispute, array $data, $matchId)
    {
        $dispute->set('match_id', $matchId);

        $this->patchEntity($dispute, $data, [
            'fieldList' => [
                'player_a_score',
                'player_b_score',
                'message'
            ],
            'validate' => 'add'
        ]);
    }

    /**
     * @return void
     */
    public function patchEntityEdit(Dispute $dispute, array $data)
    {
        $this->patchEntity($dispute, $data, [
            'fieldList' => ['is_resolved'],
            'validate' => 'edit'
        ]);
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationAdd(Validator $validator)
    {
        $validator
            ->requirePresence('player_a_score')
            ->notEmpty('player_a_score')
            ->nonNegativeInteger('player_a_score');

        $validator
            ->requirePresence('player_b_score')
            ->notEmpty('player_b_score')
            ->nonNegativeInteger('player_b_score');

        $validator->allowEmpty('message');

        return $validator;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationEdit(Validator $validator)
    {
        $validator
            ->requirePresence('is_resolved')
            ->notEmpty('is_resolved')
            ->boolean('is_resolved');

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['match_id'], 'Matches'));

        return $rules;
    }

    /**
     * @return bool
     */
    public function close(Dispute $dispute)
    {
        if ($dispute->getErrors()) {
            return false;
        }

        $this->connection()->transactional(function () use ($dispute) {
            $match = $this->Matches->get($dispute->match_id, [
                'finder' => 'populated'
            ]);

            if (!$match->created->wasWithinLast('48 hours')) {
                $dispute->set('is_resolved', false);

                $this->Matches->softDelete($match);

                $this->Matches->PlayerAs->Users->updateReputation($match->player_a->user_id, -10);
            } elseif (!$dispute->is_resolved) {
                $this->Matches->softDelete($match);

                $this->Matches->PlayerAs->Users->updateReputation($match->player_a->user_id, -10);
                $this->Matches->PlayerBs->Users->updateReputation($match->player_b->user_id, -10);
            } else {
                $this->Matches->PlayerAs->revert($match, 'player_a');
                $this->Matches->PlayerBs->revert($match, 'player_b');

                $match->set('player_a_score', $dispute->player_a_score);
                $match->set('player_b_score', $dispute->player_b_score);

                $this->Matches->save($match);
                $this->Matches->saveTree($match);
            }

            $this->save($dispute);
        });

        return true;
    }

    /**
     * @return \Cake\ORM\Query
     */
    public function findByUserId(Query $query, array $options)
    {
        if (!isset($options['userId'])) {
            throw Exception('Missing userId key in options');
        }

        $query->join([
            'Matches' => [
                'table' => 'matches',
                'type' => 'INNER',
                'conditions' => [
                    'Disputes.match_id = Matches.id'
                ]
            ],
            'Players' => [
                'table' => 'players',
                'type' => 'INNER',
                'conditions' => [
                    'OR' => [
                        ['Matches.player_a_id = Players.id'],
                        ['Matches.player_b_id = Players.id']
                    ],
                    'Players.user_id' => $options['userId']
                ]
            ]
        ]);

        return $query;
    }

    /**
     * @return \Cake\ORM\Query
     */
    public function findWithinLastWeek(Query $query, array $options)
    {
        $query->where([
            $this->aliasField('created') . ' >=' => new Time('-1 week')
        ]);

        return $query;
    }

    /**
     * @return bool
     */
    public function isClosed($matchId)
    {
        return $this->exists([
            'match_id' => $matchId,
            'is_resolved IS NOT' => null
        ]);
    }
}
