<?php
use Migrations\AbstractMigration;

class AddCurrentClubIdToPlayers extends AbstractMigration
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
        $table = $this->table('players');
        $table->addColumn('current_club_id', 'integer', [
            'after' => 'id',
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addForeignKey('current_club_id', 'clubs', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);
        $table->update();
    }
}
