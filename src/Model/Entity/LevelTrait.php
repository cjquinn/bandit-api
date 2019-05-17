<?php

namespace App\Model\Entity;

use Cake\Core\Configure;

trait LevelTrait
{
    /**
     * @return array
     */
    protected function _getLevel()
    {
        return $this->wins + $this->losses === 0
            ? ['name' => 'Unrated', 'slug' => 'unrated']
            : $this->getLevelByRating($this->rating);
    }

    /**
     * @return array
     */
    private function getLevelByRating($rating)
    {
        $levels = Configure::read('Bandit.levels');
        $low = 0;
        $high = count($levels) - 1;

        while ($low <= $high) {
            $mid = floor(($low + $high) / 2);
            $level = $levels[$mid];

            if ($rating >= $level['from'] &&
                $rating <= $level['to']
            ) {
                return [
                    'name' => $level['name'],
                    'slug' => $level['slug']
                ];
            }

            if ($rating < $level['from']) {
                $high = $mid - 1;
            } else {
                $low = $mid + 1;
            }
        }

        return [
            'name' => 'Unknown',
            'slug' => 'unknown'
        ];
    }
}
