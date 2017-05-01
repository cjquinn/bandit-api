<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $result_id
 * @property int $player_a_score
 * @property int $player_b_score
 * @property boolean $is_resolved
 */
class Dispute extends Entity
{

    protected $_accessible = [
        '*' => false
    ];
}
