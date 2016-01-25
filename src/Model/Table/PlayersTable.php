<?php

namespace App\Model\Table;

use App\Model\Entity\Player;

use ArrayObject;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class PlayersTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'belongsTo' => [
                'Logins',
                'LosingProfilePictures' => [
                    'className' => 'Files',
                    'foreignKey' => 'losing_profile_picture_id'
                ],
                'WinningProfilePictures' => [
                    'className' => 'Files',
                    'foreignKey' => 'winning_profile_picture_id'
                ]
            ]
        ]);
    }

    /**
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('login', 'create')
            ->notEmpty('login');

        $validator->add('losing_profile_picture', 'file', [
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

        $validator->add('winning_profile_picture', 'file', [
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

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['login_id'], 'Logins'));
        $rules->add($rules->existsIn(['losing_profile_picture_id'], 'LosingProfilePictures'));
        $rules->add($rules->existsIn(['winning_profile_picture_id'], 'WinningProfilePictures'));

        return $rules;
    }

    /**
     * @return void
     */
    public function beforeSave(Event $event, Player $player, ArrayObject $options)
    {
        if ($player->isNew()) {
            $player->set('rating', Configure::read('Bandit.initialRating'));
        }

        if ($player->winning_profile_picture && $player->winning_profile_picture->isNew()) {
            $player->winning_profile_picture->set('player_id', $player->id);
        }
    }
}
