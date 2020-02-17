<?php
use Migrations\AbstractMigration;

class AddRacketwareSessionIdToSnapshots extends AbstractMigration
{
    /**
     * @return void
     */
    public function change()
    {
        $table = $this->table('snapshots');

        $table->addColumn('racketware_session_id', 'integer', [
            'after' => 'player_id',
            'default' => null,
            'limit' => 11,
            'null' => true
        ]);
        $table->addForeignKey('racketware_session_id', 'racketware_sessions', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);

        $table->update();
    }
}
