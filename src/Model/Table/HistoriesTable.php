<?php

namespace App\Model\Table;

use ArrayObject;

use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class HistoriesTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'belongsTo' => [
                'Players',
                'Results'
            ]
        ]);

        $this->primaryKey([
            'player_id',
            'result_id'
        ]);
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['player_id'], 'Players'));
        $rules->add($rules->existsIn(['result_id'], 'Results'));

        return $rules;
    }

    /**
     * @return void
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        $data['difference'] = $data['player']->rating - $data['player']->getOriginal('rating');
        $data['rating'] = $data['player']->rating;
    }
}
