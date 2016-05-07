<?php

namespace App\Model\Table;

use App\Model\Entity\Result;

use ArrayObject;

use Cake\Database\Schema\Table as Schema;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;

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
        $data['snapshot'] = $data['player']->club->snapshot;
        $data['is_winner'] = $data['player']->club->rating > $data['player']->club->getOriginal('rating');
    }

    /**
     * @return \Cake\Database\Schema\Table
     */
    protected function _initializeSchema(Schema $schema)
    {
        $schema->columnType('snapshot', 'json');
        
        return $schema;
    }
}
