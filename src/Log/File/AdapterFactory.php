<?php

namespace EndpointTesting\Log\File;

use EndpointTesting\Log\File\Adapter\Iis as IisAdapter;
use EndpointTesting\Log\File\Adapter\Apache as ApacheAdapter;

class AdapterFactory
{
    protected $adapters;
    public function __construct()
    {
        $this->adapters = [
            'iss'    => new IisAdapter,
            'apache' => new ApacheAdapter,
        ];
    }

    public function factory($input = [])
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter->isParsable($input)) {
                return clone $adapter;
            }
        }
    }
}
