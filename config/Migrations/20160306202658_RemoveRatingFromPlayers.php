<?php
use Migrations\AbstractMigration;

class RemoveRatingFromPlayers extends AbstractMigration
{
    /**
     * @return void
     */
    public function up()
    {
        $table = $this->table('players');
        $table->removeColumn('rating');
        $table->update();
    }

    /**
     * @return void
     */
    public function down()
    {
        $table = $this->table('players');
        $table->addColumn('rating', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->update();
    }
}
