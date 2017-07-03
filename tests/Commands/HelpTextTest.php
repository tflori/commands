<?php

namespace tflori\Commands\Test\Commands;

use PHPUnit\Framework\TestCase;
use tflori\Commands\Command;
use tflori\Commands\Commands;
use Ulrichsg\Getopt\Option;

class HelpTextTest extends TestCase
{
    public function testContainsNoOptionAndNoCommand()
    {
        $commands = new Commands();
        $commands->setScriptName('test');

        $helpText = $commands->getHelpText();

        $expected = "Usage: test [operands]\n";
        self::assertSame($expected, $helpText);
    }

    public function testContainsOptions()
    {
        $commands = new Commands();
        $commands->setScriptName('test');
        $commands->addOptions([Option::create('a', 'optionA')->setDescription('The description for optionA')]);

        $helpText = $commands->getHelpText();

        $expected = "Usage: test [options] [operands]\n" .
                    "Options:\n" .
                    "  -a, --optionA           The description for optionA\n";
        self::assertSame($expected, $helpText);
    }

    public function testContainsCommand()
    {
        $commands = new Commands();
        $commands->setScriptName('test');
        $commands->addCommand(new Command('help', 'Show the help for command', 'var_dump'));

        $helpText = $commands->getHelpText();

        $expected = "Usage: test <command> [operands]\n" .
                    "Commands:\n" .
                    "  help                    Show the help for command\n";
        self::assertSame($expected, $helpText);
    }

    public function testContainsDescription()
    {
        $commands = new Commands();
        $commands->setScriptName('copy');
        $commands->setDescription('copy files');

        $helpText = $commands->getHelpText();

        $expected = "Usage: copy [operands]\n" .
                    "copy files\n";
        self::assertSame($expected, $helpText);
    }

    public function testShowsExecutedCommand()
    {
        $commands = new Commands();
        $commands->setScriptName('test');
        $commands->addCommand(new Command('help', 'Show the help for command', 'var_dump'));

        $commands->parse('help');
        $helpText = $commands->getHelpText();

        $expected = "Usage: test help [operands]\n" .
                    "Show the help for command\n";
        self::assertSame($expected, $helpText);
    }

    public function testWithCommonAndComandOptions()
    {
        $commands = new Commands([
            Option::create('a', 'optionA')->setDescription('The description for optionA')
        ]);
        $commands->setScriptName('test');
        $commands->addCommand(new Command('help', 'Show the help for command', 'var_dump', [
            Option::create('b', 'optionB')->setDescription('The description for optionB')
        ]));

        $commands->parse('help');
        $helpText = $commands->getHelpText();

        $expected = "Usage: test help [options] [operands]\n" .
                    "Show the help for command\n" .
                    "Options:\n" .
                    "  -a, --optionA           The description for optionA\n" .
                    "  -b, --optionB           The description for optionB\n";
        self::assertSame($expected, $helpText);
    }
}
