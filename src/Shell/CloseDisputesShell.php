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
            ->innerJoinWith('Matches', function ($q) {
                $q->where([
                    'Matches.created <' => new DateTime('48 hours ago')
                ]);

                return $q;
            })
            ->where(['is_resolved IS' => null]);

        foreach ($disputes as $dipute) {
            $dipute->set('is_resolved', false);

            $this->Disputes->close($dipute);
        }
    }
}
