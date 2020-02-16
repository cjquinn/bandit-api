<?php
use Migrations\AbstractMigration;

class AddHasAcceptedTermsToUsers extends AbstractMigration
{
    /**
     * @return void
     */
    public function change()
    {
        $table = $this->table('users');

        $table->addColumn('has_accepted_terms', 'boolean', [
            'after' => 'password',
            'default' => false,
            'null' => false
        ]);

        $table->update();
    }
}
