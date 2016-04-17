<?php
namespace App\View\Helper;
use Cake\Filesystem\File;
use Cake\View\Helper;
class SvgHelper extends Helper
{
    /**
     * @param string $file The svg file
     * @return string
     */
    public function display($file)
    {
        $svg = new File(WWW_ROOT . 'img' . DS . $file . '.svg');
        return preg_replace('/<title>.*<\/title>/', '', $svg->read());
    }
}