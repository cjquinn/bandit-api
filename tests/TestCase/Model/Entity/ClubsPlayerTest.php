<?php

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\ClubsPlayer;

use Cake\TestSuite\TestCase;

class ClubsPlayerTest extends TestCase
{

    /**
     * @return void
     */
    public function testKfactor()
    {
        // A new player (< 30 games)
        $clubsPlayer = new ClubsPlayer([
            'rating' => 1200,
            'losses' => 0,
            'wins' => 0
        ]);

        $expected = 40;

        $this->assertEquals($expected, $clubsPlayer->k_factor);

        $clubsPlayer->losses = 29;

        $this->assertEquals($expected, $clubsPlayer->k_factor);

        // Rating <2400 and >=30 games
        $clubsPlayer->losses = 30;

        $expected = 20;

        $this->assertEquals($expected, $clubsPlayer->k_factor);

        $clubsPlayer->rating = 2399;

        $this->assertEquals($expected, $clubsPlayer->k_factor);

        // Rating >=2400 and >=30 games
        $clubsPlayer->rating = 2400;

        $expected = 10;
    }
}
