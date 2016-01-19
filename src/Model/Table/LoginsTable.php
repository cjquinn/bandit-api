<?php

namespace App\Model\Table;

use App\Model\Entity\Login;

use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Utility\Text;
use Cake\Validation\Validator;

class LoginsTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }

    /**
     * @return void
     */
    public function beforeSave(Event $event, Login $login)
    {
        if ($login->isNew()) {
            $this->createToken($login);
        }
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('email')
            ->notEmpty('email', 'You must enter an email')
            ->add('email', 'valid', [
                'message' => 'You must enter a valid email',
                'rule' => 'email'
            ]);

        return $validator;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationSetPassword(Validator $validator)
    {
        $validator
            ->requirePresence('password')
            ->notEmpty('password');

        return $validator;
    }

    /**
     * Creates a token for a login
     *
     * @param \App\Model\Entity\Login $login The Login to associate a token with
     * @return void
     */
    public function createToken(Login $login)
    {
        $login->set([
            'token' => str_replace('-', '', Text::uuid()),
            'token_sent' => Time::now()
        ], ['guard' => false]);
    }

    /**
     * Finder used for Auth component
     *
     * @param \Cake\ORM\Query $query The query object
     * @param array $options The options array
     */
    public function findAuth(Query $query, array $options)
    {
        $query
            ->where(['password IS NOT' => null]);

        return $query;
    }

    /**
     * Sets a login's password
     *
     * @param \App\Model\Entity\Login $login The Login to update
     * @param string $password The new password
     * @return void
     */
    public function setPassword(Login $login, $password)
    {
        $this->patchEntity($login, [
            'password' => $password
        ], [
            'validate' => 'setPassword'
        ]);

        $login->set([
            'token' => null,
            'token_sent' => null
        ], ['guard' => false]);
    }

    /**
     * Validates a token
     *
     * @param array $query The request query object $this->request->query
     * @return bool|\App\Model\Entity\Login
     */
    public function validateToken($query)
    {
        $login = $this
            ->findByToken(Hash::get($query, 'token'))
            ->first();

        return $login;
    }
}