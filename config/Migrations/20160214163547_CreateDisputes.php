<?php

use Migrations\AbstractMigration;

class CreateDisputes extends AbstractMigration
{
    /**
     * @return void
     */
    public function change()
    {
        $table = $this->table('disputes');

        $table->addColumn('result_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false
        ]);

        $table->addForeignKey('result_id', 'results', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);

        $table->addColumn('player_a_score', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true
        ]);

        $table->addColumn('player_b_score', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true
        ]);

        $table->addColumn('message', 'text', [
            'default' => null,
            'null' => true
        ]);

        $table->addColumn('is_resolved', 'boolean', [
            'default' => null,
            'null' => true
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
