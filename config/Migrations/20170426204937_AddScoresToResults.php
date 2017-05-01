<?php
use Migrations\AbstractMigration;

class AddScoresToResults extends AbstractMigration
{
    /**
     * @return void
     */
    public function change()
    {
        $table = $this->table('results');

        $table->addColumn('winning_player_score', 'integer', [
            'default' => null,
            'null' => false,
            'limit' => 11,
            'after' => 'winning_player_id'
        ]);

        $table->addColumn('losing_player_score', 'integer', [
            'default' => null,
            'null' => false,
            'limit' => 11,
            'after' => 'winning_player_id'
        ]);

        $table->update();
    }
}
