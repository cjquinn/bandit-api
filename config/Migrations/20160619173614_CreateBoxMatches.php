<?php
use Migrations\AbstractMigration;

class CreateBoxMatches extends AbstractMigration
{
    /**
     * @return void
     */
    public function up()
    {
        $table = $this->table('box_matches', [
            'id' => false,
            'primary_key' => [
                'box_id',
                'losing_player_id',
                'winning_player_id'
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
        $table->create();

        $this->query('CREATE INDEX box_id ON box_matches (box_id)');
    }

    /**
     * @return void
     */
    public function down()
    {
        $this->dropTable('box_matches');
    }
}
