<?php

namespace EndpointTesting\Log;

class Parser
{
    protected $file;

    public function __construct(File $file = null)
    {
        if ($file) {
            $this->setFile($file);
        }
    }

    public function setFile(File $file = null)
    {
        $this->file = $file;
        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getUrl($line, $pattern)
    {
        preg_match($pattern, $line, $matches);
        if (is_array($matches) && isset($matches[1])) {
            return $matches[1];
        }
    }

    public function getUrls(File $file)
    {
        $results = [];
        $lines = $file->getLines();
        $adapter = $file->getAdapter();
        $pattern = $adapter->getRegexPattern();

        foreach ($lines as $line) {
            $url = $this->getUrl($line, $pattern);
            $results[] = $adapter->clean($url);
        }
        $results = array_unique($results);
        return $results;
    }
}
