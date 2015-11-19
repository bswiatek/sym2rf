<?php
namespace Khepin\BookBundle\Command;

class Shrinker
{
    protected $imagine;

    public function __construct($imagine) {
        $this->imagine = $imagine;
    }

    public function shrinkImage($path, $out, $size) {
        $image = $this->imagine->open($path);
        $box = new \Imagine\Image\Box($size, $size);
        $filename = basename($path);
        $image->resize($box)->save($out.'/'.basename($path));
    }
}