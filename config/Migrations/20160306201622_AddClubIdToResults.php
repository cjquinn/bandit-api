<?php
use Migrations\AbstractMigration;

class AddClubIdToResults extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('results');
        $table->addColumn('club_id', 'integer', [
            'after' => 'id',
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('club_id', 'clubs', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);
        $table->update();
    }
}
