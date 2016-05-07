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
