<?php

namespace App\Model\Entity;

use Aws\S3\S3Client;

use Cake\Core\Configure;
use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $player_id
 * @property string $name
 * @property int $size
 * @property string $type
 */
class File extends Entity
{

    protected $_accessible = [
        'id' => false,
        'player_id' => false,
        'player' => false,
        '*' => true,
    ];

    /**
     * @return string
     */
    protected function _getKey()
    {
        return Configure::read('Aws.S3.keyBase') . DS . $this->player_id . DS . $this->name;
    }

    /**
     * @return string
     */
    protected function _getUrl()
    {
        $s3 = new S3Client([
            'credentials' => Configure::read('Aws.credentials'),
            'region' => Configure::read('Aws.region'),
            'version' => 'latest'
        ]);

        $command = $s3->getCommand('GetObject', [
            'Bucket' => Configure::read('Aws.S3.bucket'),
            'Key' => $this->key
        ]);

        $request = $s3->createPresignedRequest($command, '+20 minutes');

        return (string)$request->getUri();
    }
}
