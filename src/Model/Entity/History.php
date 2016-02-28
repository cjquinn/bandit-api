<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

use DateTime;

/**
 * @property int $player_id
 * @property int $result_id
 * @property int $difference
 * @property int $rating
 */
class History extends Entity
{

    protected $_accessible = [
        '*' => true
    ];
}
