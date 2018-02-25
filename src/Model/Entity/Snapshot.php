<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $match_id
 * @property int $player_id
 * @property int $rating
 * @property int $difference
 * @property int $wins
 * @property int $losses
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Snapshot extends Entity
{

    /**
     * @var array
     */
    protected $_accessible = [
        '*' => true
    ];

    /**
     * @return array
     */
    protected function _getStats()
    {
        return [
            'rating' => $this->rating,
            'difference' => $this->difference,
            'wins' => $this->wins,
            'losses' => $this->losses
        ];
    }
}
