<?php

namespace AppBundle\Common;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UpLoad
{
    protected $file;

    public function __construct($file)
    {   
        $this->file = $file;
    }

    public function moveToPath($path,$fileName)
    {
        return $this->file->move($path,$fileName);
    }

    public function getClientOriginalName()
    {
        return $this->file->getClientOriginalName();
    }

    public function getClientMimeType()
    {
        return $this->file->getClientMimeType();
    }
}