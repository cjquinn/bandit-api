<?php

use Migrations\AbstractMigration;

class CreateClubsPlayers extends AbstractMigration
{
    /**
     * @return void
     */
    public function up()
    {
        $table = $this->table('clubs_players', [
            'id' => false,
            'primary_key' => [
                'club_id',
                'player_id'
            ]
        ]);
        $table->addColumn('club_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('club_id', 'clubs', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);
        $table->addColumn('player_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('player_id', 'players', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);
        $table->addColumn('rating', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->create();

        $this->query('CREATE INDEX club_id ON clubs_players (club_id)');
    }

    /**
     * @return void
     */
    public function down()
    {
        $this->dropTable('clubs_players');
    }
}
