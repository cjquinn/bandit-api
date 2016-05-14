<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $box_league_id
 * @property int $division
 */
class Box extends Entity
{

    protected $_accessible = [
        '*' => false
    ];
}
