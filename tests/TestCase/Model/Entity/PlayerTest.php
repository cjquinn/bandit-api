<?php

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Player;

use Cake\TestSuite\TestCase;

class PlayerTest extends TestCase
{
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
