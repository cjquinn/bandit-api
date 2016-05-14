<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $club_id
 * @property \Cake\I18n\Time $start
 * @property \Cake\I18n\Time $end
 */
class BoxLeague extends Entity
{

    protected $_accessible = [
        'start' => true,
        'end' => true,
        '*' => false
    ];
}
