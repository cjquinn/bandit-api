<?php
use Migrations\AbstractMigration;

class CreateRacketwareSessions extends AbstractMigration
{
    /**
     * @return void
     */
    public function change()
    {
        $table = $this->table('racketware_sessions');

        $table->addColumn('racketware_player_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false
        ]);

        $table->addColumn('action', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false
        ]);

        $table->addColumn('data', 'json', [
            'default' => null,
            'null' => false
        ]);

        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false
        ]);

        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false
        ]);

        $table->create();
    }
}
