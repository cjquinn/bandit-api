<?php

namespace App\Model\Table;

use App\Model\Entity\RacketwareSession;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class RacketwareSessionsTable extends Table
{
    /**
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->hasMany('Snapshots');

        $this->addBehavior('Timestamp');
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('player')
            ->notEmpty('player')
            ->nonNegativeInteger('player');

        $validator
            ->requirePresence('action')
            ->notEmptyString('action')
            ->inList('action', ['upload']);

        $validator
            ->requirePresence('data')
            ->notEmptyArray('data');

        return $validator;
    }

    /**
     * @return void
     */
    public function patchEntityAdd(RacketwareSession $racketwareSession, array $data)
    {
        $racketwareSession->set([
            'racketware_player_id' => $data['player'],
            'data' => $data['data']
        ], ['guard' => false]);
    }
}
