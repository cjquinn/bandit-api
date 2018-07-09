<?php
use Migrations\AbstractMigration;

class AddFirstNameLastNameToUsers extends AbstractMigration
{
    /**
     * @return void
     */
    public function up()
    {
        $table = $this->table('users');

        $table->renameColumn('name', 'first_name');

        $table->addColumn('last_name', 'string', [
            'after' => 'first_name',
            'default' => null,
            'limit' => 255,
            'null' => true
        ]);

        $table->update();
    }

    /**
     * @return void
     */
    public function down()
    {
        $table = $this->table('users');

        $table->renameColumn('first_name', 'name');

        $table->removeColumn('last_name');

        $table->update();
    }
}
