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
    use LevelTrait;

    /**
     * @var array
     */
    protected $_accessible = [
        '*' => true
    ];

    protected $_virtual = [
        'level',
        'previous_level',
        'previous_rating'
    ];

    /**
     * @return array
     */
    protected function _getPreviousLevel()
    {
        return $this->getLevelByRating($this->previous_rating);
    }

    /**
     * @return int
     */
    protected function _getPreviousRating()
    {
        return $this->rating - $this->difference;
    }

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
