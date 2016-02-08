<?php

namespace App\Model\Table;

use App\Model\Entity\Player;

use ArrayObject;

use Aws\S3\S3Client;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
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
                'Logins'
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

        $validator
            ->allowEmpty('losing_profile_picture')
            ->add('losing_profile_picture', 'file', [
                'rule' => [
                    'uploadedFile',
                    [
                        'optional' => true,
                        'types' => [
                            'image/jpeg',
                            'image/png'
                        ]
                    ]
                ]
            ]);

        $validator
            ->allowEmpty('winning_profile_picture')
            ->add('winning_profile_picture', 'file', [
                'rule' => [
                    'uploadedFile',
                    [
                        'optional' => true,
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
    }

    /**
     * @param \App\Model\Entity\Player $player The player object
     * @param string $tmpName The tmp name
     * @param string $type (Losing | winning)
     * @return void
     */
    public function setProfilePicture(Player $player, $tmpName, $type)
    {
        $avatar = $this->_createAvatar($tmpName);
        $this->_putFile($player->id . DS . $type . '_profile_picture.jpg', $avatar, 'image/jpeg');
    }

    /**
     * @param \App\Model\Entity\Player $losingPlayer The losing player
     * @param \App\Model\Entity\Player $winningPlayer The winning player
     * @param bool $isDraw If the result was a draw
     * @return void
     */
    public function updateRatings(Player $losingPlayer, Player $winningPlayer, $isDraw = false)
    {
        $score = $isDraw ? 0.5 : 0;
        $this->_updateRating($losingPlayer, $score, $this->_expectedScore($winningPlayer, $losingPlayer));

        $score = $isDraw ? 0.5 : 1;
        $this->_updateRating($winningPlayer, $score, $this->_expectedScore($losingPlayer, $winningPlayer));
    }

    /**
     * Creates an avatar from an uploaded image
     *
     * @param string $tmpName The tmp_name of the file uploaded
     * @return string
     */
    private function _createAvatar($tmpName)
    {
        list($width, $height, $type) = getimagesize($tmpName);

        $image = $type === IMAGETYPE_JPEG ? imagecreatefromjpeg($tmpName) : imagecreatefrompng($tmpName);

        $aspectRatio = $width / $height;

        if ($aspectRatio > 1) {
            $resizedWidth = 150 * $aspectRatio;
            $resizedHeight = 150;
        } else {
            $resizedWidth = 150;
            $resizedHeight = 150 / $aspectRatio;
        }

        $resizedImage = imagecreatetruecolor($resizedWidth, $resizedHeight);
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $resizedWidth, $resizedHeight, $width, $height);

        $croppedImage = imagecreatetruecolor(150, 150);
        imagecopy($croppedImage, $resizedImage, 0, 0, ($resizedWidth - 150) / 2, ($resizedHeight - 150) / 2, 150, 150);

        ob_start();
        imagejpeg($croppedImage);
        return ob_get_clean();
    }

    /**
     * @param \App\Model\Entity\Player $losingPlayer The losing player
     * @param \App\Model\Entity\Player $winningPlayer The winning player
     * @return float
     */
    private function _expectedScore(Player $losingPlayer, Player $winningPlayer)
    {
        return 1 / (1 + (pow(10, ($losingPlayer->rating - $winningPlayer->rating) / 400)));
    }

    /**
     * Puts a file in an S3 bucket
     *
     * @param string $key The key of the file
     * @param string $body The body of the file
     * @param string $type The type of the file
     * @return \Aws\Result
     */
    private function _putFile($key, $body, $type)
    {
        $s3 = new S3Client([
            'credentials' => Configure::read('Aws.credentials'),
            'region' => Configure::read('Aws.region'),
            'version' => 'latest'
        ]);

        $result = $s3->putObject([
            'Bucket' => Configure::read('Aws.S3.bucket'),
            'Key' => Configure::read('Aws.S3.keyBase') . $key,
            'Body' => $body,
            'ContentType' => $type
        ]);

        $s3->waitUntil('ObjectExists', [
            'Bucket' => Configure::read('Aws.S3.bucket'),
            'Key' => Configure::read('Aws.S3.keyBase') . $key,
            'ContentType' => $type
        ]);

        return $result;
    }

    /**
     * @param \App\Model\Entity\Player $player The player
     * @param float $score (0 | 0.5 | 1)
     * @param float $expectedScore The players expected score
     * @return void
     */
    private function _updateRating(Player $player, $score, $expectedScore)
    {
        $player->set('rating', $player->rating + (32 * ($score - $expectedScore)));
        $this->save($player);
    }
}
