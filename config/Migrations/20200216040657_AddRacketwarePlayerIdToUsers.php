<?php

use Migrations\AbstractMigration;

class AddRacketwarePlayerIdToUsers extends AbstractMigration
{
    /**
     * @return void
     */
    public function up()
    {
        $table = $this->table('users');

        $table->addColumn('racketware_player_id', 'integer', [
            'after' => 'id',
            'default' => null,
            'limit' => 11,
            'null' => true
        ]);
        $table->addIndex(['racketware_player_id'], ['unique' => true]);

        $table->update();
    }

    /**
     * @return void
     */
    public function down()
    {
        $table = $this->table('users');

        $table->removeColumn('racketware_player_id');

        $table->save();
    }
}
