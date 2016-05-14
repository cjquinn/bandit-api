<?php
use Migrations\AbstractMigration;

class CreateBoxesPlayers extends AbstractMigration
{
    /**
     * @return void
     */
    public function up()
    {
        $table = $this->table('boxes_players', [
            'id' => false,
            'primary_key' => [
                'box_id',
                'player_id'
            ]
        ]);
        $table->addColumn('box_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('box_id', 'boxes', 'id', [
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
        $table->addColumn('points', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->create();
        $this->query('CREATE INDEX box_id ON boxes_players (box_id)');
    }

    /**
     * @return void
     */
    public function down()
    {
        $this->dropTable('boxes_players');
    }
}
