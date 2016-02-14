<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $player_id
 * @property int $result_id
 * @property boolean $is_resolved
 */
class Dispute extends Entity
{

    protected $_accessible = [
        '*' => false
    ];
}
