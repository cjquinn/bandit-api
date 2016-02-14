<?php
use Migrations\AbstractMigration;

class CreateDisputes extends AbstractMigration
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
        $table = $this->table('disputes', [
            'id' => false,
            'primary_key' => [
                'player_id',
                'result_id'
            ]
        ]);
        $table->addColumn('player_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('player_id', 'players', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);
        $table->addColumn('result_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('result_id', 'results', 'id', [
            'update' => 'RESTRICT',
            'delete' => 'RESTRICT'
        ]);
        $table->addColumn('is_resolved', 'boolean', [
            'default' => null,
            'null' => true,
        ]);
        $table->create();
    }
}
