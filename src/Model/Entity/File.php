<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $player_id
 * @property string $name
 * @property int $size
 * @property string $type
 */
class File extends Entity
{

    protected $_accessible = [
        'id' => false,
        'player_id' => false,
        'player' => false,
        '*' => true,
    ];
}
