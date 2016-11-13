<?php
use Migrations\AbstractMigration;

class AddLossesWinsToClubsPlayers extends AbstractMigration
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
        $table = $this->table('clubs_players');
        $table->addColumn('losses', 'integer', [
            'after' => 'player_id',
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('wins', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->update();
    }
}
