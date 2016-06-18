<?php
use Migrations\AbstractMigration;

class AddSubmittedToResults extends AbstractMigration
{
    /**
     * @return void
     */
    public function up()
    {
        $table = $this->table('results');
        $table->addColumn('submitted', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->removeColumn('created');
        $table->removeColumn('modified');
        $table->update();
    }

    /**
     * @return void
     */
    public function down()
    {
        $table = $this->table('results');
        $table->removeColumn('submitted');
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->update();
    }
}
