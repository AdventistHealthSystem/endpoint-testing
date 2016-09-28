<?php

namespace EndpointTesting\Console;

use EndpointTesting\Console\Command\TestCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;

class EndpointApplication extends Application
{
    protected function getCommandName(InputInterface $input)
    {
        return 'test';
    }

    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();
        $commands[] = new TestCommand;
        return $commands;
    }

    public function getDefinition()
    {
        $definition = parent::getDefinition();
        $definition->setArguments();
        return $definition;
    }
}