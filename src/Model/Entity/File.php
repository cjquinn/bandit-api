<?php

namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $player_id
 * @property string $name
 * @property int $size
 * @property string $type
 */
class File extends Entity
{

    protected $_accessible = [
        'id' => false,
        'player_id' => false,
        'player' => false,
        '*' => true,
    ];

    /**
     * @return string
     */
    protected function _getKey()
    {
        return Configure::read('Aws.S3.keyBase') . DS . $this->player_id . DS . $this->name;
    }
}
