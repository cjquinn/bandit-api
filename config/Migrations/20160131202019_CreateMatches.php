<?php

use Migrations\AbstractMigration;

class CreateMatches extends AbstractMigration
{
    /**
     * @return void
     */
    public function change()
    {
        $table = $this->table('matches');

        $table->addColumn('club_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false
        ]);
        $table->addForeignKey('club_id', 'clubs', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);

        $table->addColumn('player_a_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false
        ]);
        $table->addForeignKey('player_a_id', 'players', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);

        $table->addColumn('player_b_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false
        ]);
        $table->addForeignKey('player_b_id', 'players', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);

        $table->addColumn('player_a_score', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false
        ]);

        $table->addColumn('player_b_score', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false
        ]);

        $table->addColumn('player_a_snapshot', 'text', [
            'default' => null,
            'null' => false
        ]);

        $table->addColumn('player_b_snapshot', 'text', [
            'default' => null,
            'null' => false
        ]);

        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false
        ]);

        $table->addColumn('deleted', 'datetime', [
            'default' => null,
            'null' => true
        ]);

        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false
        ]);

        $table->create();
    }
}
