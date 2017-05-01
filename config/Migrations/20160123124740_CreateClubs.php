<?php

use Migrations\AbstractMigration;

class CreateClubs extends AbstractMigration
{
    /**
     * @return void
     */
    public function change()
    {
        $table = $this->table('clubs');

        $table->addColumn('founder_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false
        ]);
        $table->addForeignKey('founder_id', 'users', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);

        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 255,
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
