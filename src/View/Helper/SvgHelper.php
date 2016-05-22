<?php
namespace App\View\Helper;
use Cake\Filesystem\File;
use Cake\View\Helper;
class SvgHelper extends Helper
{
    private $_viewBoxes = [
        
        'brand-logo' => '0 0 129 95',
        'brand-knot' => '0 0 37 32',

        'icon-matches' => '0 0 21 18',
        'icon-players' => '0 0 148 180',
        'icon-rating' => '0 0 11 13',

        'rarr' => '0 0 8 13',
        'player' => '0 0 91 107',

        'menu' => '0 0 18 18',

        'plus' => '0 0 12 12',
        'tick' => '0 0 11 8',
        'cross' => '0 0 8 8',

    ];

    /**
     * @param string $file The svg file
     * @return string
     */
    public function display($file)
    {
        $svg = new File(WWW_ROOT . 'img' . DS . $file . '.svg');
        return preg_replace('/<title>.*<\/title>/', '', $svg->read());
    }

    public function useit($id, $class)
    {
        return '<svg viewBox="'. $this->_viewBoxes[$id] . '" class="' . $class . '"><use xlink:href="#' . $id . '"></use></svg>';
    }
}