<?php

namespace App\Model\Table;

use App\Model\Entity\User;

use ArrayObject;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Security;
use Cake\Utility\Text;
use Cake\Validation\Validator;

use Firebase\JWT\JWT;

class UsersTable extends Table
{
    use MailerAwareTrait;

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->addAssociations([
            'hasMany' => [
                'Players'
            ]
        ]);

        $this->addBehavior('Timestamp');
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationAcceptTerms(Validator $validator)
    {
        $validator
            ->requirePresence('has_accepted_terms')
            ->notEmpty('has_accepted_terms')
            ->add('has_accepted_terms', 'invalid', [
                'rule' => function ($value) {
                    return $value === true;
                },
                'message' => 'You must accept the terms of service'
            ]);

        return $validator;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationAdd(Validator $validator)
    {
        $validator
            ->requirePresence('first_name')
            ->notEmpty('first_name');

        $validator
            ->requirePresence('last_name')
            ->notEmpty('last_name');

        $validator
            ->requirePresence('email')
            ->notEmpty('email')
            ->email('email');

        $validator
            ->requirePresence('password')
            ->notEmpty('password');

        $validator = $this->validationAcceptTerms($validator);

        return $validator;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationEdit(Validator $validator)
    {
        $validator
            ->notEmpty('first_name');

        $validator
            ->notEmpty('last_name');

        $validator
            ->notEmpty('email')
            ->email('email');

        $emailPreferencesValidator = new Validator();
        $emailPreferencesValidator
            ->requirePresence('challenge_created')
            ->notEmpty('challenge_created')
            ->boolean('challenge_created');

        $emailPreferencesValidator
            ->requirePresence('match_added')
            ->notEmpty('match_added')
            ->boolean('match_added');

        $emailPreferencesValidator
            ->requirePresence('weekly_digest')
            ->notEmpty('weekly_digest')
            ->boolean('weekly_digest');

        $validator
            ->notEmpty('email_preferences')
            ->addNested('email_preferences', $emailPreferencesValidator);

        $validator
            ->requirePresence('current_password', function ($context) {
                return isset($context['data']['new_password']);
            })
            ->notEmpty('current_password', 'You must enter your current password');

        $validator
            ->requirePresence('new_password', function ($context) {
                return isset($context['data']['current_password']);
            })
            ->notEmpty('new_password', 'You must enter a new password');

        return $validator;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationInvite(Validator $validator)
    {
        $validator
            ->requirePresence('email')
            ->notEmpty('email')
            ->email('email');

        return $validator;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationRequestPasswordReset(Validator $validator)
    {
        $validator
            ->requirePresence('email')
            ->notEmpty('email')
            ->email('email');

        return $validator;
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationResetPassword(Validator $validator)
    {
        $validator
            ->requirePresence('password')
            ->notEmpty('password');

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->isUnique(['token']));

        return $rules;
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
    public function patchEntityAdd(User $user, array $data)
    {
        $this->patchEntity($user, $data, ['validate' => 'add']);

        if (!empty($user->getErrors())) {
            return;
        }

        $user->set('email_preferences', Configure::read('Bandit.email_preferences'));

        $existingUser = $this
            ->findByEmail($user->email)
            ->first();

        if (!$existingUser ||
            $existingUser->is_activated
        ) {
            return;
        }

        $user->set('id', $existingUser->id);
    }

    /**
     * @return void
     */
    public function patchEntityEdit(User $user, array $data)
    {
        $user->setAccess(['current_password', 'new_password'], true);

        $this->patchEntity($user, $data, ['validate' => 'edit']);

        if (!$user->current_password ||
            !$user->new_password
        ) {
            return;
        }

        if (!(new DefaultPasswordHasher)->check($user->current_password, $user->password)) {
            $user->unsetProperty(['current_password', 'new_password']);

            $user->setError('current_password', [
                'match' => 'The password you entered was incorrect'
            ]);

            return;
        }

        $user->set('password', $user->new_password);
    }

    /**
     * @return void
     */
    public function patchEntityResetPassword(User $user, array $data)
    {
        $user->setAccess('password', true);

        $this->patchEntity($user, $data, ['validate' => 'resetPassword']);

        if (empty($user->getErrors())) {
            $this->patchEntityClearToken($user);
        }
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
     * @return void
     */
    public function afterSave(Event $event, User $user, ArrayObject $options)
    {
        if ($user->token &&
            $user->isDirty('token')
        ) {
            $this->getMailer('User')->send(
                'resetPassword',
                [$user]
            );
        }
    }

    /**
     * @return void
     */
    public function updateReputation($id, $difference)
    {
        $user = $this->get($id);

        $user->set('reputation', $user->reputation + $difference);

        $this->save($user);
    }

    /**
     * @param string|null $token
     * @return bool|\App\Model\Entity\User
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @throws \Cake\Network\Exception\UnauthorizedException
     */
    public function getByToken($token)
    {
        $user = $this
            ->find('auth')
            ->where(['token' => $token])
            ->firstOrFail();

        if ($user->token_sent >= Time::createFromTimestamp(strtotime('1 hour ago'))) {
            return $user;
        }

        $this->patchEntitySetToken($user);

        $this->save($user);

        throw new UnauthorizedException('This link has expired. Check your email for your new link.');
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
}
