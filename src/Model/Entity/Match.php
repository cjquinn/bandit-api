<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $club_id
 * @property int $player_a_id
 * @property int $player_b_id
 * @property int $player_a_score
 * @property int $player_b_score
 * @property \Cake\I18n\Time $deleted
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Match extends Entity
{

    protected $_accessible = [
        'player_b_id' => true,
        'player_a_score' => true,
        'player_b_score' => true,
        '*' => false
    ];
}
