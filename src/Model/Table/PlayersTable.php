<?php

namespace App\Model\Table;

use App\Model\Entity\Player;

use ArrayObject;

use Aws\S3\S3Client;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

use DateTime;

class PlayersTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addAssociations([
            'belongsTo' => [
                'CurrentClubs' => [
                    'className' => 'Clubs',
                    'foreignKey' => 'current_club_id'
                ],
                'Logins'
            ],
            'belongsToMany' => [
                'Clubs'
            ],
            'hasMany' => [
                'Histories',
                'Results'
            ],
            'hasOne' => [
                'Club' => [
                    'className' => 'ClubsPlayers',
                    'foreignKey' => 'player_id',
                    'propertyName' => 'club'
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
        $rules->add($rules->existsIn(['current_club_id'], 'Clubs'));
        $rules->add($rules->existsIn(['login_id'], 'Logins'));
        
        return $rules;
    }

    /**
     * @return int
     */
    public function dailySnapshot(Player $player, $clubId, DateTime $date)
    {
        $result = $this->Results
            ->findByClubId($clubId)
            ->contain([
                'Histories' => function ($q) use ($player) {
                    $q->where([
                        'Histories.player_id' => $player->id
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
                    ['losing_player_id' => $player->id],
                    ['winning_player_id' => $player->id]
                ],
                'created <' => $date
            ])
            ->first();

        return $result ? $result->history->snapshot : ['losses' => 0, 'rating' => Configure::read('Bandit.initialRating'), 'wins' => 0];
    }

    /**
     * @return float
     */
    public function expectedScore($losingPlayersRating, $winningPlayersRating)
    {
        return 1 / (1 + pow(10, ($winningPlayersRating - $losingPlayersRating) / 400));
    }

    /**
     * Find players with clubs_players row
     *
     * @param \Cake\ORM\Query $query The query object
     * @param array $options The options array
     */
    public function findClub(Query $query, $options)
    {
        $query->contain([
            'Club' => function ($q) use ($options) {
                $q->where([
                    'Club.club_id' => $options['clubId']
                ]);

                return $q;
            }
        ]);

        return $query;
    }

    /**
     * @return bool
     */
    public function hasDisputes($id, $clubId)
    {
        return
            !$this->Results
                ->find()
                ->where([
                    'club_id' => $clubId,
                    'winning_player_id' => $id
                ])
                ->innerJoinWith('Disputes')
                ->isEmpty();
    }

    /**
     * @return bool
     */
    public function isAssignedTo($id, $clubId)
    {
        return $this->Club->exists([
            'club_id' => $clubId,
            'player_id' => $id
        ]);
    }

    /**
     * @return bool
     */
    public function isFounder($id, $clubId)
    {
        return $this->Clubs->exists([
            'id' => $clubId,
            'founding_player_id' => $id
        ]);
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
     * @return float
     */
    public function updatedRating($rating, $expectedScore, $score)
    {
        return $rating + round(32 * ($score - $expectedScore));
    }

    /**
     * @param \App\Model\Entity\Player $losingPlayer The losing player
     * @param \App\Model\Entity\Player $winningPlayer The winning player
     * @param int $clubId The club id
     * @param \DateTime $date The date of the result
     * @return void
     */
    public function updateRatings(Player $losingPlayer, Player $winningPlayer, $clubId, DateTime $date)
    {
        $this->loadInto([$losingPlayer, $winningPlayer], [
            'Club' => function ($q) use ($clubId) {
                $q->where([
                    'Club.club_id' => $clubId
                ]);

                return $q;
            }
        ]);

        $losingPlayersExpectedScore = $this->expectedScore(
            $this->dailySnapshot($losingPlayer, $clubId, $date)['rating'],
            $this->dailySnapshot($winningPlayer, $clubId, $date)['rating']
        );
        $winningPlayersExpectedScore = 1 - $losingPlayersExpectedScore;

        $losingPlayer->club->set('rating', $this->updatedRating($losingPlayer->club->rating, $losingPlayersExpectedScore, 0));
        $winningPlayer->club->set('rating', $this->updatedRating($winningPlayer->club->rating, $winningPlayersExpectedScore, 1));

        $losingPlayer->club->set('losses', $losingPlayer->club->losses + 1);
        $winningPlayer->club->set('wins', $winningPlayer->club->wins + 1);

        $losingPlayer->dirty('club', true);
        $winningPlayer->dirty('club', true);
    }

    /**
     * @return void
     */
    public function updateReputation(Player $player, $difference)
    {
        $player->set('reputation', $player->reputation + $difference);
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
}
