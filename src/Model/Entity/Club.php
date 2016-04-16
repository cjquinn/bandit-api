<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $founding_player_id
 * @property string $name
 */
class Club extends Entity
{

    protected $_accessible = [
        'name' => true,
        '*' => false
    ];
}
