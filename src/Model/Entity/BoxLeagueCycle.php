<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $club_id
 * @property date $start
 * @property date $end
 */
class BoxLeagueCycle extends Entity
{

    protected $_accessible = [
        '*' => false
    ];
}
