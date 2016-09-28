<?php

namespace EndpointTesting\Log;

class File
{
    const ERR_BAD_PATH = 'The path [%s] is invalid.';
    const ERR_ADAPTER_NOT_SET = 'The adapter is not set for this file';

    protected $path;
    protected $adapter;

    public function __construct($path = '')
    {
        $this->setPath($path);
        $this->setFileAdapter();
    }

    public function setFileAdapter()
    {
        $factory = $this->getFileAdapterFactory();
        $lines = $this->getLines();
        $this->adapter = $factory->factory($lines);
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function getFileAdapterFactory()
    {
        return new File\AdapterFactory;
    }

    public function getPattern()
    {
        $adapter = $this->getAdapter();
        if (! $adapter) {
            throw new Exception(self::ERR_ADAPTER_NOT_SET);
        }

        return $adapter->getPattern();
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
