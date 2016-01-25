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
        'name' => true,
        'login' => true,
        'losing_profile_picture' => true,
        'winning_profile_picture' => true,
        '*' => false
    ];
}
