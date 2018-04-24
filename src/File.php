<?php

namespace Wilkinsocks\Doc;

use SplFileObject;
use SplFileInfo;

class File
{
    protected $file;

    public function __construct(String $path)
    {
        $this->file = $this->loadFile($path);
    }

    protected function loadFile(String $path)
    {
        return new SplFileObject($path);
    }

    public function getFile()
    {
        return $this->file;
    }
}
