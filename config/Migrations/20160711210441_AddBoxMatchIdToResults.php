<?php
use Migrations\AbstractMigration;

class AddBoxMatchIdToResults extends AbstractMigration
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
        $table->addColumn('box_match_id', 'integer', [
            'after' => 'id',
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addForeignKey('box_match_id', 'box_matches', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);
        $table->update();
    }
}
