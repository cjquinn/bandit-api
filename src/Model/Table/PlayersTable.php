<?php

namespace App\Model\Table;

use ArrayObject;

use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;

class PlayersTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'belongsTo' => [
                'Clubs',
                'Users'
            ]
        ]);

        $this->addBehavior('Timestamp');
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['club_id'], 'Clubs'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * @return void
     */
    public function beforeSave(Event $event, EntityInterface $player, ArrayObject $options)
    {
        if ($player->isNew()) {
            $player->set('rating', Configure::read('Bandit.initialRating'));
        }
    }

    /**
     * @return array
     * @see https://en.wikipedia.org/wiki/Elo_rating_system#Mathematical_details
     */
    public function expectedScores($ratingA, $ratingB)
    {
        $a = 1 / (1 + pow(10, ($ratingB - $ratingA) / 400));
        $b = 1 - $a;

        return [
            'a' => $a,
            'b' => $b
        ];
    }

    /**
     * @return int
     * @see https://en.wikipedia.org/wiki/Elo_rating_system#Mathematical_details
     */
    public function ratingChange($expectedScore, $score, $kFactor)
    {
        return round($kFactor * ($score - $expectedScore));
    }
}
