<?php

use Migrations\AbstractMigration;

class UpdateHistories extends AbstractMigration
{
    /**
     * @return void
     */
    public function up()
    {
        $table = $this->table('histories');
        $table->addColumn('snapshot', 'text', [
            'default' => null,
            'null' => false
        ]);
        $table->addColumn('is_winner', 'boolean', [
            'default' => null,
            'null' => false
        ]);
        $table->removeColumn('difference');
        $table->removeColumn('rating');
        $table->save();
    }

    /**
     * @return void
     */
    public function down()
    {
        $table = $this->table('histories');
        $table->addColumn('difference', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('rating', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->removeColumn('snapshot');
        $table->removeColumn('is_winner');
        $table->save();
    }
}
