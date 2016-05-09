<?php
namespace App\View\Helper;
use Cake\Filesystem\File;
use Cake\View\Helper;
class InitialHelper extends Helper
{
    /**
     * @param string $file The svg file
     * @return string
     */
    public function display()
    {

    	$int = rand(0,51);
    	$int2 = rand(0,51);
	    $a_z = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	    $rand_letter1 = $a_z[$int];
	    $rand_letter2 = $a_z[$int2];
	    return $rand_letter1 . $rand_letter2;

    }
}