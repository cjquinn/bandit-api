<?php
use Migrations\AbstractMigration;

class CreateBoxesPlayersResults extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('boxes_players_results', [
            'id' => false,
            'primary_key' => [
                'boxes_player_box_id',
                'boxes_player_player_id',
                'result_id'
            ]
        ]);
        $table->addColumn('boxes_player_box_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('boxes_player_box_id', 'boxes_players', 'box_id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);
        $table->addColumn('boxes_player_player_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('boxes_player_player_id', 'boxes_players', 'player_id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);
        $table->addColumn('result_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('result_id', 'results', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);
        $table->create();

        $this->query('CREATE INDEX boxes_player_box_id ON boxes_players_results (boxes_player_box_id)');
    }
}
