<?php

namespace EndpointTesting\Log\File\Adapter;

use EndpointTesting\Log\File\AdapterInterface as AdapterInterface;

class Apache implements AdapterInterface
{
    const REGEX_PATTERN = '/GET ([\/A-Za-z0-9\-\.\_\?\=\&]+)/i';

    public function getRegexPattern()
    {
        return self::REGEX_PATTERN;
    }

    public function isParsable($input)
    {
        if (is_array($input)) {
            $input = $input[0];
        }
        $pattern = '/^(?:[0-9]{1,3}\.){3}[0-9]{1,3}/';
        preg_match($pattern, $input, $matches);
        if (isset($matches[0])) {
            return true;
        }
        return false;
    }

    public function clean($url)
    {
        return trim($url);
    }
}
