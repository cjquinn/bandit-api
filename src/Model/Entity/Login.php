<?php

namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $token
 * @property datetime $token_sent
 * @property datetime $created
 * @property datetime $modified
 */
class Login extends Entity
{

    protected $_accessible = [
        'email' => true,
        'password' => true,
        '*' => false,
    ];

    protected $_hidden = [
        'password',
        'token',
        'token_sent'
    ];

    /**
     * @return string
     */
    protected function _setPassword($password)
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher)->hash($password);
        }
    }
    /**
     * @return bool
     */
    protected function _getIsActivated()
    {
        return !is_null($this->password);
    }
}
