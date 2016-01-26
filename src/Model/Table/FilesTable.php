<?php

namespace App\Model\Table;

use App\Model\Entity\File;

use ArrayObject;

use Aws\S3\S3Client;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class FilesTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'belongsTo' => [
                'Players'
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
            ->requirePresence('size', 'create')
            ->notEmpty('size')
            ->add('size', 'valid', ['rule' => 'numeric']);

        $validator
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        return $validator;
    }

    /**
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['player_id'], 'Players'));

        return $rules;
    }

    /**
     * @return void
     */
    public function beforeSave(Event $event, File $file, ArrayObject $options)
    {
        if ($file->isNew()) {
            if ($file->source() === 'LosingProfilePictures' ||
                $file->source() === 'WinningProfilePictures'
            ) {
                $avatar = $this->_createAvatar($file->tmp_name);

                $file->set([
                    'name' => ($file->source() === 'LosingProfilePictures' ? 'losing' : 'winning') . '_profile_picture.png',
                    'size' => strlen($avatar),
                    'type' => 'image/png'
                ]);

                $this->_putFile($file->key, $avatar);
            }
        }
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
        imagepng($croppedImage);
        return ob_get_clean();
    }

    /**
     * Puts a file in an S3 bucket
     *
     * @param string $key The key of the file
     * @param string $body The body of the file
     * @return \Aws\Result
     */
    private function _putFile($key, $body)
    {
        $s3 = new S3Client([
            'credentials' => Configure::read('Aws.credentials'),
            'region' => Configure::read('Aws.region'),
            'version' => 'latest'
        ]);

        $result = $s3->putObject([
            'Bucket' => Configure::read('Aws.S3.bucket'),
            'Key' => $key,
            'Body' => $body
        ]);

        $s3->waitUntil('ObjectExists', [
            'Bucket' => Configure::read('Aws.S3.bucket'),
            'Key' => $key
        ]);

        return $result;
    }
}
