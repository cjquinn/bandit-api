<?php
namespace App\View\Helper;
use Cake\Filesystem\File;
use Cake\View\Helper;
class RatingHelper extends Helper
{
    /**
     * @param string $file The svg file
     * @return string
     */
    public function display($size)
    {

    	$int = rand(0,51);
    	$int2 = rand(0,51);

    	if ($size == 'low') {
    		$rating = rand(700,1200);
    	}

        else if ($size == 'medium') {
            $rating = rand(1201,1499);
        }

    	else if ($size == 'high') {
    		$rating = rand(1500,1800);
    	}

	    return $rating * 5;

    }
}