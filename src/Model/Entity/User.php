<?php

namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property int $reputation
 * @property string $email
 * @property json $email_preferences
 * @property string $password
 * @property boolean $has_accepted_terms
 * @property string $token
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \Cake\I18n\Time $token_sent
 */
class User extends Entity
{
    /**
     * @var array
     */
    protected $_accessible = [
        'first_name' => true,
        'last_name' => true,
        'email' => true,
        'email_preferences' => true,
        'password' => true,
        'has_accepted_terms' => true,
        '*' => false
    ];

    /**
     * @var array
     */
    protected $_hidden = [
        'password',
        'token',
        'token_sent'
    ];

    /**
     * @var array
     */
    protected $_virtual = [
        'display_name',
        'full_name'
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

    /**
     * @return string
     */
    protected function _getDisplayName()
    {
        return sprintf(
            '%s %s.',
            $this->first_name,
            substr($this->last_name, 0, 1)
        );
    }

    /**
     * @return string
     */
    protected function _getFullName()
    {
        return sprintf(
            '%s %s',
            $this->first_name,
            $this->last_name
        );
    }
}
