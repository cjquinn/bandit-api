<?php
use Migrations\AbstractMigration;

class CreateBoxes extends AbstractMigration
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
        $table = $this->table('boxes');
        $table->addColumn('box_league_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('box_league_id', 'box_leagues', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);
        $table->addColumn('division', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->create();
    }
}
