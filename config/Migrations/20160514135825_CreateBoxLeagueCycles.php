<?php
use Migrations\AbstractMigration;

class CreateBoxLeagueCycles extends AbstractMigration
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
        $table = $this->table('box_league_cycles');
        $table->addColumn('club_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('club_id', 'clubs', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);
        $table->addColumn('start', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('end', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->create();
    }
}
