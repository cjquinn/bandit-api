<?php

namespace App\Model\Table;

use App\Model\Entity\Login;

use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Utility\Security;
use Cake\Utility\Text;
use Cake\Validation\Validator;

use Firebase\JWT\JWT;

class LoginsTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'hasOne' => [
                'Players'
            ]
        ]);

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
    public function validationActivate(Validator $validator)
    {
        $validator = $this->validationPassword($validator);

        if (!defined('TESTING')) {
            $validator
                ->requirePresence('losing_profile_picture')
                ->notEmpty('losing_profile_picture')
                ->add('losing_profile_picture', 'file', [
                    'rule' => [
                        'uploadedFile',
                        [
                            'types' => [
                                'image/jpeg',
                                'image/png'
                            ]
                        ]
                    ]
                ]);

            $validator
                ->requirePresence('winning_profile_picture')
                ->notEmpty('winning_profile_picture')
                ->add('winning_profile_picture', 'file', [
                    'rule' => [
                        'uploadedFile',
                        [
                            'types' => [
                                'image/jpeg',
                                'image/png'
                            ]
                        ]
                    ]
                ]);
        }

        return $validator;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('email', 'create')
            ->notEmpty('email')
            ->add('email', 'valid', [
                'message' => 'You must enter a valid email',
                'rule' => 'email'
            ]);

        return $validator;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationPassword(Validator $validator)
    {
        $validator
            ->requirePresence('password')
            ->notEmpty('password');

        return $validator;
    }

    /**
     * Activates an account
     *
     * @param \App\Model\Entity\Login $login The login object
     * @param array $data The data array
     * @return void
     */
    public function activateAccount(Login $login, array $data)
    {
        $this->patchEntity($login, $data, [
            'validate' => 'activate'
        ]);

        $login->set([
            'token' => null,
            'token_sent' => null
        ], ['guard' => false]);
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
            ->contain(['Players'])
            ->where(['password IS NOT' => null]);

        return $query;
    }

    /**
     * Generates a JWT
     *
     * @return string
     */
    public function generateToken($id)
    {
        return JWT::encode(
            [
                'sub' => $id,
                'exp' =>  time() + 604800
            ],
            Security::salt()
        );
    }

    /**
     * Sets a login's password
     *
     * @param \App\Model\Entity\Login $login The Login to update
     * @param array $data The data array
     * @return void
     */
    public function setPassword(Login $login, array $data)
    {
        $this->patchEntity($login, $data, [
            'validate' => 'password'
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
            ->contain(['Players'])
            ->first();

        return $login;
    }
}
