<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $player_id
 * @property int $result_id
 * @property text $snapshot
 */
class History extends Entity
{

    protected $_accessible = [
        '*' => true
    ];
}
