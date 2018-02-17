<?php

namespace App\Model\Table;

use App\Model\Entity\Player;

use Cake\ORM\Query;
use Cake\ORM\Table;

class LeaderboardsTable extends Table
{

    /**
     * @return void
     */
    public function initialize(array $config)
    {
        $this->setTable('players');
    }
}
