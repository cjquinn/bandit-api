<?php

namespace App\Model\Entity;

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
}
