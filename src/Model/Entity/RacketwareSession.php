<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $racketware_player_id
 * @property string $action
 * @property array $data
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class RacketwareSession extends Entity
{
    /**
     * @var array
     */
    protected $_accessible = [
        '*' => false
    ];
}
