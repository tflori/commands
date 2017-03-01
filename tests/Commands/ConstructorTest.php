<?php

namespace tflori\Commands\Test\Commands;

use PHPUnit\Framework\TestCase;
use tflori\Commands\Commands;

class ConstructorTest extends TestCase
{
    public function testStoresArgv0ForScriptName()
    {
        $commands = new Commands();

        $help = $commands->getHelpText();

        self::assertContains($_SERVER['argv'][0], $help);
    }
}
