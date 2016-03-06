<?php

use Migrations\AbstractMigration;

class CreateDisputes extends AbstractMigration
{
    /**
     * @return void
     */
    public function up()
    {
        $table = $this->table('disputes', [
            'id' => false,
            'primary_key' => [
                'player_id',
                'result_id'
            ]
        ]);
        $table->addColumn('player_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('player_id', 'players', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);
        $table->addColumn('result_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('result_id', 'results', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);
        $table->addColumn('message', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('is_resolved', 'boolean', [
            'default' => null,
            'null' => true,
        ]);
        $table->create();
        
        $this->query('CREATE INDEX player_id ON disputes (player_id)');
    }

    /**
     * @return void
     */
    public function down()
    {
        $this->dropTable('disputes');
    }
}
