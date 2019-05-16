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
    use LevelTrait;

    protected $_accessible = [
        '*' => false
    ];

    protected $_virtual = [
        'games',
        'level'
    ];

    /**
     * @return int
     */
    protected function _getGames()
    {
        return $this->wins + $this->losses;
    }

    /**
     * @return array|void
     */
    protected function _getHighestLevel()
    {
        return $this->getLevelByRating($this->highest_rating);
    }
}
