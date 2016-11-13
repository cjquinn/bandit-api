<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $club_id
 * @property int $losing_player_id
 * @property int $winning_player_id
 * @property datetime $submitted
 */
class Result extends Entity
{

    protected $_accessible = [
        'losing_player_id' => true,
        '*' => false
    ];
}
