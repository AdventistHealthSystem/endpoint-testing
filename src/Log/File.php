<?php

namespace EndpointTesting\Log;

class File
{
    const ERR_BAD_PATH = 'The path [%s] is invalid.';

    protected $path;

    public function __construct($path = '')
    {
        $this->setPath($path);
    }

    public function setPath($path)
    {
        $path = realpath($path);
        $this->validatePath($path);
        $this->path = $path;
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getLines()
    {
        $path = $this->getPath();
        return file($path);
    }

    public function validatePath($path)
    {
        if (! $this->isValidPath($path)) {
            throw new Exception(sprintf(self::ERR_BAD_PATH, $path));
        }

        return $this;
    }

    public function isValidPath($path)
    {
        if (!$path) {
            return false;
        }

        return true;
    }
}