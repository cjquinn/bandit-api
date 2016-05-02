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
                'result_id'
            ]
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
    }

    /**
     * @return void
     */
    public function down()
    {
        $this->dropTable('disputes');
    }
}
