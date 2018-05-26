<?php

namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $club_id
 * @property int $user_id
 * @property int $rating
 * @property int $wins
 * @property int $losses
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Player extends Entity
{

    protected $_accessible = [
        '*' => false
    ];

    protected $_virtual = [
        'level'
    ];

    /**
     * @return int
     * @see https://en.wikipedia.org/wiki/Elo_rating_system#Mathematical_details
     */
    protected function _getKFactor()
    {
        if ($this->losses + $this->wins < 30) {
            return 40;
        }

        if ($this->rating < 2400) {
            return 20;
        }

        return 10;
    }

    /**
     * @return array
     */
    protected function _getLevel()
    {
        $levels = Configure::read('Bandit.levels');
        $low = 0;
        $high = count($levels) - 1;

        while ($low <= $high) {
            $mid = floor(($low + $high) / 2);
            $level = $levels[$mid];

            if ($this->rating >= $level['from'] &&
                $this->rating <= $level['to']
            ) {
                return [
                    'name' => $level['name'],
                    'slug' => $level['slug']
                ];
            }

            if ($this->rating < $level['from']) {
                $high = $mid - 1;
            } else {
                $low = $mid + 1;
            }
        }

        return [
            'name' => 'Unknown',
            'slug' => 'unknown'
        ];
    }
}
