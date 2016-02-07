<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $loser_id
 * @property int $winner_id
 * @property \Cake\I18n\Time $date
 */
class Result extends Entity
{
    protected $_accessible = [
        'id' => false,
        'winner_id' => false,
        '*' => true
    ];
}
