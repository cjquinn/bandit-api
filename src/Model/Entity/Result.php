<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $receiver_id
 * @property int $sender_id
 * @property int $winner_id
 * @property \Cake\I18n\Time $date
 */
class Result extends Entity
{
    protected $_accessible = [
        'id' => false,
        'sender_id' => false,
        'winner_id' => false,
        '*' => true
    ];
}
