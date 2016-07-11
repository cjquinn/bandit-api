<?php
use Migrations\AbstractMigration;

class CreateBoxMatches extends AbstractMigration
{
    /**
     * @return void
     */
    public function change()
    {
        $table = $this->table('box_matches');
        $table->addColumn('box_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('box_id', 'boxes', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);
        $table->addColumn('losing_player_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('losing_player_id', 'players', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);
        $table->addColumn('winning_player_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('winning_player_id', 'players', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);
        $table->addColumn('disputed', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('submitted', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->create();
    }
}
