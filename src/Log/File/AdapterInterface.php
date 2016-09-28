<?php

namespace EndpointTesting\Log\File;

interface AdapterInterface
{
    public function getRegexPattern();

    public function isParsable($input);

    public function clean($url);
}
