<?php

namespace App\Test\TestCase\Model\Table;

use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class PlayersTableTest extends TestCase
{

    public $fixtures = [
        'app.clubs',
        'app.disputes',
        'app.players',
        'app.matches',
        'app.snapshots',
        'app.users'
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Players = TableRegistry::get('Players');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->Players);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testPatchEntityAdd()
    {
        // Required
        $player = $this->Players->newEntity();
        $data = [];
        $clubId = 1;

        $this->Players->patchEntityAdd($player, $data, $clubId);

        $expected = [
            'user' => [
                '_required' => 'This field is required'
            ]
        ];
        $this->assertEquals($expected, $player->getErrors());
        $this->assertEquals($clubId, $player->club_id);

        // Nested
        $player = $this->Players->newEntity();
        $data = [
            'user' => 'A user'
        ];
        $clubId = 1;

        $this->Players->patchEntityAdd($player, $data, $clubId);

        $expected = [
            'user' => [
                '_nested' => 'The provided value is invalid'
            ]
        ];
        $this->assertEquals($expected, $player->getErrors());
        $this->assertEquals($clubId, $player->club_id);

        // New user
        $player = $this->Players->newEntity();
        $data = [
            'user' => [
                'email' => 'some@new.player'
            ]
        ];
        $clubId = 1;

        $this->Players->patchEntityAdd($player, $data, $clubId);

        $this->assertNotNull($player->user);
        $this->assertEquals('some@new.player', $player->user->email);
        $this->assertNotNull($player->user->token);
        $this->assertNotNull($player->user->token_sent);
        $this->assertEquals($clubId, $player->club_id);

        // Existing user non member
        $player = $this->Players->newEntity();
        $data = [
            'user' => [
                'email' => 'gareth@banditmatch.com'
            ]
        ];
        $clubId = 1;
        $userId = $this->Players->Users
            ->findByEmail('gareth@banditmatch.com')
            ->enableHydration(false)
            ->first()['id'];

        $this->Players->patchEntityAdd($player, $data, $clubId);

        $this->assertNull($player->user);
        $this->assertEquals($userId, $player->user_id);
        $this->assertEquals($clubId, $player->club_id);

        // Existing user member
        $player = $this->Players->newEntity();
        $data = [
            'user' => [
                'email' => 'christy@banditmatch.com'
            ]
        ];
        $clubId = 1;

        $this->Players->patchEntityAdd($player, $data, $clubId);

        $expected = [
            'user' => [
                'email' => [
                    'duplicate' => 'A member of this club already exists with that email'
                ]
            ]
        ];
        $this->assertEquals($expected, $player->getErrors());
    }

    /**
     * @return void
     */
    public function testBeforeSave()
    {
        $player = $this->Players->newEntity();

        $player->set('club_id', 1);
        $player->set('user_id', 1);

        $this->Players->save($player);

        $this->assertEquals($player->rating, Configure::read('Bandit.initialRating'));
    }

    /**
     * @return void
     */
    public function testExpectedScores()
    {
        $expectedScores = $this->Players->expectedScores(1200, 1200);

        $this->assertTrue(is_array($expectedScores));

        $expected = [
            'a' => 0.5,
            'b' => 0.5
        ];
        $this->assertEquals($expected, $expectedScores);

        $expectedScores = $this->Players->expectedScores(1216, 1184);

        $expected = [
            'a' => 0.5459219228,
            'b' => 0.4540780772
        ];
        $this->assertEquals($expected, $expectedScores);

        $expectedScores = $this->Players->expectedScores(1215, 1185);

        $expected = [
            'a' => 0.543066492,
            'b' => 0.456933508
        ];
        $this->assertEquals($expected, $expectedScores);
    }

    /**
     * @return void
     */
    public function testRatingChange()
    {
        // Expected scores
        $expectedScores = $this->Players->expectedScores(1200, 1200);

        // Player A wins
        $ratingChange = $this->Players->ratingChange($expectedScores['a'], 1, 40);

        $expected = 20;
        $this->assertEquals($expected, $ratingChange);

        // Player B loses
        $ratingChange = $this->Players->ratingChange($expectedScores['b'], 0, 40);

        $expected = -20;
        $this->assertEquals($expected, $ratingChange);

        // Expected scores
        $expectedScores = $this->Players->expectedScores(1216, 1184);

        // Player A draws
        $ratingChange = $this->Players->ratingChange($expectedScores['a'], 0.5, 40);

        $expected = -2;
        $this->assertEquals($expected, $ratingChange);

        // Player B draws
        $ratingChange = $this->Players->ratingChange($expectedScores['b'], 0.5, 40);

        $expected = 2;
        $this->assertEquals($expected, $ratingChange);

        // Expected scores
        $expectedScores = $this->Players->expectedScores(1215, 1185);

        // Player A loses
        $ratingChange = $this->Players->ratingChange($expectedScores['a'], 0, 40);

        $expected = -22;
        $this->assertEquals($expected, $ratingChange);

        // Player B wins
        $ratingChange = $this->Players->ratingChange($expectedScores['b'], 1, 40);

        $expected = 22;
        $this->assertEquals($expected, $ratingChange);
    }

    /**
     * @return void
     */
    public function testRevert()
    {
        $match = $this->Players->Clubs->Matches->get(1, ['finder' => 'populated']);

        $this->assertTrue($this->Players->revert($match, 'player_a'));

        $playerA = $this->Players->get($match->player_a_id);

        $this->assertEquals(1200, $playerA->rating);
        $this->assertEquals(0, $playerA->wins);
        $this->assertEquals(0, $playerA->losses);

        $this->assertTrue($this->Players->revert($match, 'player_b'));

        $playerB = $this->Players->get($match->player_b_id);

        $this->assertEquals(1200, $playerB->rating);
        $this->assertEquals(0, $playerB->wins);
        $this->assertEquals(0, $playerB->losses);

        // Deleted match
        $match->set('deleted', new Time());

        $this->assertTrue($this->Players->revert($match, 'player_a'));

        $playerA = $this->Players->get($match->player_a_id, [
            'contain' => ['Users']
        ]);

        $this->assertEquals(2, $playerA->user->reputation);
    }

    /**
     * @return void
     */
    public function testSnapshotPlayer()
    {
        $player = $this->Players->get(1);

        $snapshot = $this->Players->snapshotPlayer($player, 0.5, 4, 2);

        $this->assertEquals(1255, $player->rating);
        $this->assertEquals(6, $player->wins);
        $this->assertEquals(3, $player->losses);

        $expected = [
            'rating' => 1255,
            'difference' => 40,
            'wins' => 6,
            'losses' => 3
        ];
        $this->assertEquals($expected, $snapshot);
    }

    /**
     * @return void
     */
    public function testSnapshotPlayers()
    {
        $match = $this->Players->Clubs->Matches->newEntity([
            'player_b_id' => 7,
            'player_a_score' => 1,
            'player_b_score' => 0
        ]);

        $match->set('club_id', 1);
        $match->set('player_a_id', 6);

        $snapshots = $this->Players->snapshotPlayers($match);

        $expected = [
            'player_a_snapshot' => [
                'rating' => 1256,
                'difference' => 18,
                'wins' => 3,
                'losses' => 0
            ],
            'player_b_snapshot' => [
                'rating' => 1144,
                'difference' => -18,
                'wins' => 0,
                'losses' => 3
            ]
        ];
        $this->assertEquals($expected, $snapshots);

        // Reputation updated
        $userA = $this->Players->Users->get(6);
        $userB = $this->Players->Users->get(7);

        $this->assertEquals(3, $userA->reputation);
        $this->assertEquals(3, $userB->reputation);
    }

    /**
     * @return void
     */
    public function testFindAllTimeLeaderboard()
    {
        $query = $this->Players
            ->findByClubId(1)
            ->find('allTimeLeaderboard');

        $expected = [
            6, // Sam - 1238
            4, // Tom - 1238
            5, // Alex - 1222
            1, // Christy - 1215
            3, // Nathan - 1178
            2, // Russell - 1166
            7  // Dom - 1162
        ];

        $this->assertEquals($expected, $query->extract('id')->toArray());
    }

    /**
     * @return void
     */
    public function testFindWeeklyLeaderboard()
    {
        $clubId = 1;

        // Reset fixtures
        $matchesTable = TableRegistry::get('Matches');
        $snapshotsTable = TableRegistry::get('Snapshots');

        TableRegistry::get('Disputes')->deleteAll(['1 = 1']);
        $snapshotsTable->deleteAll(['1 = 1']);
        $matchesTable->deleteAll(['1 = 1']);
        $this->Players->updateAll([
            'rating' => 1200,
            'wins' => 0,
            'losses' => 0
        ], ['1 = 1']);

        $playMatches = function ($players) use ($clubId, $matchesTable) {
            $ids = [];

            foreach ($players as $player) {
                foreach ($player['opponents'] as $opponent) {
                    $match = $matchesTable->newEntity();

                    $matchesTable->patchEntityAdd($match, [
                        'player_b_id' => $opponent,
                        'player_a_score' => 1,
                        'player_b_score' => 0
                    ], $clubId, $player['id']);

                    $matchesTable->save($match);

                    array_push($ids, $match->id);
                }
            }

            return $ids;
        };

        // Ratings
        // 1 - 1200
        // 2 - 1200
        // 3 - 1200
        // 4 - 1200
        // 5 - 1200
        // 6 - 1200
        // 7 - 1200

        $this->printPlayerRatings($clubId, 'Start');

        // Play some matches week 1
        $players = [
            [
                // Wins 3
                'id' => 1,
                'opponents' => [2, 3, 4]
            ],
            [
                // Wins 2, Loses 1
                'id' => 2,
                'opponents' => [3, 4]
            ],
            [
                // Wins 1, Loses 2
                'id' => 3,
                'opponents' => [4]
            ],
            [
                // Wins 0, Loses 3
                'id' => 4,
                'opponents' => []
            ]
        ];

        $ids = $playMatches($players);

        // Set matches to 2 weeks ago
        $twoWeeksAgo = new Time('2 weeks ago');
        $matchesTable->updateAll([
            'created' => $twoWeeksAgo,
            'modified' =>  $twoWeeksAgo
        ], ['id IN' => $ids]);

        $snapshotsTable->updateAll([
            'created' => $twoWeeksAgo,
            'modified' =>  $twoWeeksAgo
        ], ['match_id IN' => $ids]);

        // Ratings
        // 1 - 1260
        // 2 - 1220
        // 5 - 1200
        // 6 - 1200
        // 7 - 1200
        // 3 - 1180
        // 4 - 1140

        $this->printPlayerRatings($clubId, $twoWeeksAgo);

        // Week 2 matches
        $players = [
            [
                // Wins 1 Loses 2
                'id' => 1,
                'opponents' => [4]
            ],
            [
                // Wins 1, Loses 2
                'id' => 2,
                'opponents' => [1]
            ],
            [
                // Wins 2, Loses 1
                'id' => 3,
                'opponents' => [1, 2]
            ],
            [
                // Wins 2, Loses 1
                'id' => 4,
                'opponents' => [2, 3]
            ]
        ];

        $ids = $playMatches($players);

        // Set matches to 1 week ago
        $oneWeekAgo = new Time('1 week ago');
        $matchesTable->updateAll([
            'created' => $oneWeekAgo,
            'modified' =>  $oneWeekAgo
        ], ['id IN' => $ids]);

        $snapshotsTable->updateAll([
            'created' => $oneWeekAgo,
            'modified' =>  $oneWeekAgo
        ], ['match_id IN' => $ids]);

        // Ratings
        // 1 - 1226
        // 3 - 1205
        // 5 - 1200
        // 6 - 1200
        // 7 - 1200
        // 2 - 1195
        // 4 - 1174

        $this->printPlayerRatings($clubId, $oneWeekAgo);

        // This weeks matches
        $players = [
            [
                // Wins 0, Loses 0
                'id' => 1,
                'opponents' => []
            ],
            [
                // Wins 2, Loses 1
                'id' => 2,
                'opponents' => [3, 5]
            ],
            [
                // Wins 2, Loses 3
                'id' => 3,
                'opponents' => [2, 7]
            ],
            [
                // Wins 3, Loses 3
                'id' => 4,
                'opponents' => [5, 6, 7]
            ],
            [
                // Wins 1, Loses 2
                'id' => 5,
                'opponents' => [4]
            ],
            [
                // Wins 2, Loses 1
                'id' => 6,
                'opponents' => [4, 3]
            ],
            [
                // Wins 2, Loses 2
                'id' => 7,
                'opponents' => [4, 3]
            ]
        ];

        $playMatches($players);

        // Ratings
        // 2 - 1217 - 1195 = 22
        // 6 - 1218 - 1200 = 18
        // 4 - 1180 - 1174 = 6
        // 7 - 1198 - 1200 = -2
        // 3 - 1183 - 1205 = -22
        // 5 - 1178 - 1200 = -22
        // 1 - 1226 - 1226 = 0 <- didn't play

        $this->printPlayerRatings($clubId, 'This week');

        $query = $this->Players
            ->findByClubId($clubId)
            ->find('weeklyLeaderboard');

        $expected = [
            2,
            6,
            4,
            7,
            3,
            5
        ];

        $this->assertEquals($expected, $query->extract('id')->toArray());
    }

    /**
     * @return void
     */
    public function testFindWithHighestRating()
    {
        $players = $this->Players->find('withHighestRating');

        $expected = [1, 2, 3, 4, 5, 6, 7, 8];

        $this->assertEquals($expected, $players->extract('id')->toArray());

        $expected = ['1238', '1200', '1200', '1238', '1203', '1238', '1200', '1200'];

        $this->assertEquals($expected, $players->extract('highest_rating')->toArray());

        $expected = [
            ['name' => 'Fighter', 'slug' => 'fighter'],
            ['name' => 'Fighter', 'slug' => 'fighter'],
            ['name' => 'Fighter', 'slug' => 'fighter'],
            ['name' => 'Fighter', 'slug' => 'fighter'],
            ['name' => 'Fighter', 'slug' => 'fighter'],
            ['name' => 'Fighter', 'slug' => 'fighter'],
            ['name' => 'Fighter', 'slug' => 'fighter'],
            ['name' => 'Fighter', 'slug' => 'fighter']
        ];

        $this->assertEquals($expected, $players->extract('highest_level')->toArray());
    }

    /**
     * @return void
     */
    public function printPlayerRatings($clubId, $date = null)
    {
        return;

        $players = $this->Players
            ->findByClubId($clubId)
            ->orderDesc('rating');

        echo "\n";
        echo '======================================';
        echo "\n";

        if ($date) {
            echo $date;
            echo "\n";
            echo '======================================';
            echo "\n";
        }

        foreach ($players as $player) {
            echo sprintf(
                'Player %d: rating %d, wins %d, losses %d',
                $player->id,
                $player->rating,
                $player->wins,
                $player->losses
            );
            echo "\n";
        }
    }
}
