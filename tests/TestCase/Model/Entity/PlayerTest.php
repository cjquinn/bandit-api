<?php

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Player;

use Cake\TestSuite\TestCase;

class PlayerTest extends TestCase
{

    /**
     * @return void
     */
    public function testKfactor()
    {
        // A new player (< 30 games)
        $player = new Player([
            'rating' => 1200,
            'losses' => 0,
            'wins' => 0
        ]);

        $expected = 40;

        $this->assertEquals($expected, $player->k_factor);

        $player->losses = 29;

        $this->assertEquals($expected, $player->k_factor);

        // Rating <2400 and >=30 games
        $player->losses = 30;

        $expected = 20;

        $this->assertEquals($expected, $player->k_factor);

        $player->rating = 2399;

        $this->assertEquals($expected, $player->k_factor);

        // Rating >=2400 and >=30 games
        $player->rating = 2400;

        $expected = 10;
    }

    /**
     * @return void
     */
    public function testLevel()
    {
        $player = new Player(['rating' => 1200, 'wins' => 1]);

        $expected = [
            'name' => 'Fighter',
            'slug' => 'fighter'
        ];

        $this->assertEquals($expected, $player->level);

        $player = new Player(['rating' => 1856, 'wins' => 1]);

        $expected = [
            'name' => 'Mammoth',
            'slug' => 'mammoth'
        ];

        $this->assertEquals($expected, $player->level);

        $player = new Player(['rating' => 1856213123, 'wins' => 1]);

        $expected = [
            'name' => 'God',
            'slug' => 'god'
        ];

        $this->assertEquals($expected, $player->level);

        $player = new Player(['rating' => -1856213123, 'wins' => 1]);

        $expected = [
            'name' => 'Beginner',
            'slug' => 'beginner'
        ];

        $this->assertEquals($expected, $player->level);
    }
}
