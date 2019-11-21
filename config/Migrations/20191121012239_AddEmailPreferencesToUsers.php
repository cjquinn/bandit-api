<?php

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

use Migrations\AbstractMigration;

class AddEmailPreferencesToUsers extends AbstractMigration
{
    /**
     * @return void
     */
    public function up()
    {
        $table = $this->table('users');
        $table->addColumn(
            'email_preferences',
            'json',
            [
                'after' => 'email',
                'null' => false
            ]
        );
        $table->update();

        TableRegistry::get('Users')->updateAll(
            ['email_preferences' => Configure::read('Bandit.email_preferences')],
            ['1=1']
        );
    }

    /**
     * @return void
     */
    public function down()
    {
        $this->table('users')->removeColumn('email_preferences');
    }
}
