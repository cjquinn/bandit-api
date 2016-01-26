<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $login_id
 * @property string $name
 * @property int $rating
 * @property int $reputation
 */
class Player extends Entity
{

    protected $_accessible = [
        'id' => false,
        'login_id' => false,
        'rating' => false,
        'reputation' => false,
        '*' => true
    ];
}
