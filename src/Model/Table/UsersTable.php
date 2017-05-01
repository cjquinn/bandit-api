<?php

namespace App\Model\Table;

use App\Model\Entity\User;

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

class UsersTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'hasMany' => [
                'Players'
            ]
        ]);

        $this->addBehavior('Timestamp');
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
     * @return void
     */
    public function patchEntityActivate(User $user, array $data)
    {
        $this->patchEntity($user, $data, [
            'validate' => 'activate'
        ]);

        $this->patchEntityClearToken($user);
    }

    /**
     * @return void
     */
    public function patchEntityClearToken(User $user)
    {
        $user->set([
            'token' => null,
            'token_sent' => null
        ], ['guard' => false]);
    }

    /**
     * @return void
     */
    public function patchEntityDefault(User $user, array $data)
    {
        $this->patchEntity($user, $data);
    }

    /**
     * @return void
     */
    public function patchEntityPassword(User $user, array $data)
    {
        $this->patchEntity($user, $data, [
            'validate' => 'password'
        ]);

        $this->patchEntityClearToken($user);
    }

    /**
     * @return void
     */
    public function patchEntitySetToken(User $user)
    {
        $user->set([
            'token' => str_replace('-', '', Text::uuid()),
            'token_sent' => Time::now()
        ], ['guard' => false]);
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationActivate(Validator $validator)
    {
        $validator
            ->requirePresence('name')
            ->notEmpty('name');

        $validator = $this->validationPassword($validator);

        return $validator;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('email')
            ->notEmpty('email')
            ->email('email');

        $validator = $this->validationPassword($validator);

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
     * Finder used for Auth component
     *
     * @param \Cake\ORM\Query $query The query object
     * @param array $options The options array
     */
    public function findAuth(Query $query, array $options)
    {
        $query->where(['password IS NOT' => null]);

        return $query;
    }

    /**
     * Generates a JWT
     *
     * @return string
     */
    public function generateJwt($id)
    {
        return JWT::encode([
            'sub' => $id,
            'exp' =>  time() + 604800
        ], Security::salt());
    }

    /**
     * @param array $query The request query object $this->request->query
     * @return bool|\App\Model\Entity\User
     */
    public function getByToken($query)
    {
        $user = $this
            ->findByToken(Hash::get($query, 'token'))
            ->firstOrFail();

        return $user;
    }
}
