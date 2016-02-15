<?php

namespace App\Model\Entity;

use Aws\S3\S3Client;

use Cake\Core\Configure;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

use DateTime;

/**
 * @property int $id
 * @property int $login_id
 * @property string $name
 * @property int $rating
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
     * @return int
     */
    protected function _getDailyRating()
    {
        $result = TableRegistry::get('Results')
            ->find()
            ->contain([
                'Histories' => function ($q) {
                    $q->where([
                        'Histories.player_id' => $this->id
                    ]);

                    return $q;
                }
            ])
            ->innerJoinWith('Histories')
            ->order([
                'created' => 'DESC'
            ])
            ->where([
                'OR' => [
                    ['losing_player_id' => $this->id],
                    ['winning_player_id' => $this->id]
                ],
                'created <' => new DateTime('today')
            ])
            ->first();

        return $result ? $result->history->rating : $this->rating;
    }

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
