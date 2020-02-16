<?php

use Migrations\AbstractMigration;

class AddFollowUpSentToChallenges extends AbstractMigration
{
    /**
     * @return void
     */
    public function change()
    {
        $table = $this->table('challenges');

        $table->addColumn('follow_up_sent', 'datetime', [
            'after' => 'match_datetime',
            'default' => null,
            'null' => true
        ]);

        $table->update();
    }
}
