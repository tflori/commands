# tflori/commands

[![Build Status](https://travis-ci.org/tflori/commands.svg?branch=master)](https://travis-ci.org/tflori/commands)
[![Coverage Status](https://coveralls.io/repos/github/tflori/commands/badge.svg?branch=master)](https://coveralls.io/github/tflori/commands?branch=master)
[![Latest Stable Version](https://poser.pugx.org/tflori/commands/v/stable.svg)](https://packagist.org/packages/tflori/commands) 
[![Total Downloads](https://poser.pugx.org/tflori/commands/downloads.svg)](https://packagist.org/packages/tflori/commands) 
[![License](https://poser.pugx.org/tflori/commands/license.svg)](https://packagist.org/packages/tflori/commands)

## installation

```bash
composer install tflori/commands
```

Nothing else required. You want a more complicated process? Find one by yourself.

## usage

Create a commands object:

```php
<?php
$commands = new \tflori\Commands\Commands();
```

Define options that every command can have:

```php
<?php
$commands = new \tflori\Commands\Commands();
$commands->addOptions([
    \Ulrichsg\Getopt\Option::create('h', 'help'),
    \Ulrichsg\Getopt\Option::create('q', 'quiet'),
    \Ulrichsg\Getopt\Option::create('v', 'verbose'),
]);
```

Define a command with specific options:

```php
<?php
$commands = new \tflori\Commands\Commands();
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
```

Use it:

```php
<?php
$commands = new \tflori\Commands\Commands();
$commands->parse();

if ($commands->getOption('help') || !($command = $commands->getCommand())) {
    echo $commands->getHelpText();
} else {
    call_user_func($command->getHandler(), $commands->getOptions(), $commands->getOperands());
}
```

Console examples:

```console
$ php example.php
Usage: example.php <command> [options] [operands]
Options:
  -h, --help              
  -q, --quiet             
  -v, --verbose           
Commands:
  install                 Setup the requirements (dependencies, database etc.)

$ php example.php install -h
Usage: example.php install [options] [operands]
Setup the requirements (dependencies, database etc.)
Options:
  -h, --help              
  -q, --quiet             
  -v, --verbose           
  -d, --database <arg>    Use this database name

$ php example.php install
setting up database dummy... done

php example.php install -d test
setting up database test... done
```
