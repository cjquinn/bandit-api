<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $club_id
 * @property int $player_id
 * @property int $losses
 * @property int $rating
 * @property int $wins
 */
class ClubsPlayer extends Entity
{

    protected $_accessible = [
        '*' => true
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
    protected function _getSnapshot()
    {
        return [
            'difference' => $this->rating - $this->getOriginal('rating'),
            'losses' => $this->losses,
            'rating' => $this->rating,
            'wins' => $this->wins
        ];
    }
}
