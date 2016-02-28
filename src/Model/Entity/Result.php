<?php

namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

use DateTime;

/**
 * @property int $id
 * @property int $losing_player_id
 * @property int $winning_player_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Result extends Entity
{
    protected $_accessible = [
        'losing_player_id' => true,
        '*' => false
    ];
}
