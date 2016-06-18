<?php
use Migrations\AbstractMigration;

class AddBoxIdToResults extends AbstractMigration
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
        $table->addColumn('box_id', 'integer', [
            'after' => 'id',
            'default' => null,
            'limit' => 11,
            'null' => true
        ]);
        $table->addForeignKey('box_id', 'boxes', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);
        $table->update();
    }
}
