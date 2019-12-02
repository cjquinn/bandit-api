<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $club_id
 * @property int $match_id
 * @property int $player_a_id
 * @property int $player_b_id
 * @property string $location
 * @property \Cake\I18n\Time $match_datetime
 * @property \Cake\I18n\Time $follow_up_sent
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $deleted
 * @property \Cake\I18n\Time $modified
 */
class Challenge extends Entity
{
    /**
     * @var array
     */
    protected $_accessible = [
        'location' => true,
        'match_datetime' => true,
        '*' => false
    ];
}
