<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $match_id
 * @property int $player_a_score
 * @property int $player_b_score
 * @property string $message
 * @property boolean $is_resolved
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Dispute extends Entity
{
    protected $_accessible = [
        '*' => false
    ];
}
