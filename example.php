<?php

namespace tflori;

use tflori\Commands\Commands;

require_once __DIR__ . '/vendor/autoload.php';

$commands = new Commands();

$commands->addOptions([
    \Ulrichsg\Getopt\Option::create('h', 'help'),
    \Ulrichsg\Getopt\Option::create('q', 'quiet'),
    \Ulrichsg\Getopt\Option::create('v', 'verbose'),
]);

$commands->addCommand(new \tflori\Commands\Command(
    'install',
    'Setup the requirements (dependencies, database etc.)',
    function ($options, $operands) {
        echo "setting up database " . (isset($options['database']) ? $options['database'] : 'dummy') . "...";
        echo " done\n";
    },
    [
        \Ulrichsg\Getopt\Option::create('d', 'database', \Ulrichsg\Getopt\Getopt::REQUIRED_ARGUMENT)
            ->setDescription('Use this database name')
    ]
));

$commands->parse(array_slice($_SERVER['argv'], 1));

if ($commands->getOption('help') || !($command = $commands->getCommand())) {
    echo $commands->getHelpText();
} else {
    call_user_func($command->getHandler(), $commands->getOptions(), $commands->getOperands());
}
