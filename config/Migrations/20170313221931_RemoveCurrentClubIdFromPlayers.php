<?php
use Migrations\AbstractMigration;

class RemoveCurrentClubIdFromPlayers extends AbstractMigration
{
    /**
     * @return void
     */
    public function up()
    {
        $table = $this->table('players');

        $table->dropForeignKey('current_club_id');

        $table->removeColumn('current_club_id');

        $table->update();
    }

    /**
     * @return void
     */
    public function down()
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
