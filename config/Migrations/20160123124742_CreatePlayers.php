<?php

use Migrations\AbstractMigration;

class CreatePlayers extends AbstractMigration
{
    /**
     * @return void
     */
    public function change()
    {
        $table = $this->table('players');

        $table->addColumn('club_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false
        ]);
        $table->addForeignKey('club_id', 'clubs', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);

        $table->addColumn('user_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false
        ]);
        $table->addForeignKey('user_id', 'users', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);

        $table->addColumn('rating', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false
        ]);

        $table->addColumn('wins', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false
        ]);

        $table->addColumn('losses', 'integer', [
            'default' => 0,
            'limit' => 11,
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
