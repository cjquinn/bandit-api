<?php

namespace App\Shell;

use Cake\Console\Shell;

use DateTime;

class CloseDisputesShell extends Shell
{

    public $modelClass = 'Disputes';

    /**
     * @return bool|int
     */
    public function main()
    {
        $disputes = $this->Disputes
            ->find()
            ->innerJoinWith('Results', function ($q) {
                $q->where([
                    'created <' => new DateTime('48 hours ago')
                ]);

                return $q;
            });

        foreach ($disputes as $dipute) {
            $dipute->set('is_resolved', false);
            $this->Disputes->save($dipute);
        }
    }
}
