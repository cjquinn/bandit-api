<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $box_id
 * @property int $player_id
 * @property int $points
 */
class BoxesPlayer extends Entity
{

    protected $_accessible = [
        '*' => false
    ];
}
