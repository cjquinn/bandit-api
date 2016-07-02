<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $box_id
 * @property int $losing_player_id
 * @property int $winning_player_id
 * @property datetime $disputed
 */
class BoxMatch extends Entity
{

    protected $_accessible = [
        'losing_player_id' => true,
        'losses' => true,
        'wins' => true,
        '*' => false
    ];

    /**
     * @return array
     */
    protected function _getScore()
    {
        return [
            'losses' => $this->losses ? $this->losses : 0,
            'wins' => $this->wins ? $this->wins : 0
        ];
    }
}
