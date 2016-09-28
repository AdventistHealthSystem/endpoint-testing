<?php

namespace EndpointTesting\Log\File\Adapter;

use EndpointTesting\Log\File\AdapterInterface as AdapterInterface;

class Iis implements AdapterInterface
{
    const REGEX_PATTERN = '/\/([\/_a-z0-9\.\=\&\s\-]*)\s80/';

    public function getRegexPattern()
    {
        return self::REGEX_PATTERN;
    }

    public function isParsable($input)
    {
        if (is_array($input)) {
            $input = $input[0];
        }

        if (strpos($input, 'Microsoft')) {
            return true;
        }

        $pattern = '/^([0-9)]{4})-([0-9]{2})-([0-9]{2}) '
            . '([0-9]{2}):([0-9]{2}):([0-9]{2}) '
            . '(?:[0-9]{1,3}\.){3}[0-9]{1,3} /';

        preg_match($pattern, $input, $matches);
        if (isset($matches[0])) {
            return true;
        }

        return false;
    }

    public function clean($url)
    {
        $url = trim($url);
        $url = rtrim($url, '?');
        $url = rtrim($url, '-');
        $url = trim($url);
        $url = strtr($url, [' ' => '?']);
        return trim($url);
    }
}
