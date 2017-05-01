<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $founder_id
 * @property string $name
 */
class Club extends Entity
{

    protected $_accessible = [
        'name' => true,
        '*' => false
    ];
}
