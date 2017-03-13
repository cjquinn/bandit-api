<?php

namespace App\Model\Entity;

use Aws\S3\S3Client;

use Cake\Core\Configure;
use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $login_id
 * @property string $name
 * @property int $reputation
 */
class Player extends Entity
{

    protected $_accessible = [
        'name' => true,
        'login' => true,
        '*' => false
    ];

    /**
     * @return string
     */
    protected function _getLosingProfilePictureUrl()
    {
        return $this->_getProfilePicture('losing');
    }

    /**
     * @return string
     */
    protected function _getWinningProfilePictureUrl()
    {
        return $this->_getProfilePicture('winning');
    }

    /**
     * @param string $type The type of profile picture
     * @return string
     */
    private function _getProfilePicture($type)
    {
        $s3 = new S3Client([
            'credentials' => Configure::read('Aws.credentials'),
            'region' => Configure::read('Aws.region'),
            'version' => 'latest'
        ]);

        $command = $s3->getCommand('GetObject', [
            'Bucket' => Configure::read('Aws.S3.bucket'),
            'Key' => Configure::read('Aws.S3.keyBase') . $this->id . DS . $type . '_profile_picture.jpg'
        ]);

        $request = $s3->createPresignedRequest($command, '+20 minutes');

        return (string)$request->getUri();
    }
}
