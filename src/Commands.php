<?php

namespace tflori\Commands;

use Ulrichsg\Getopt\Getopt;

class Commands extends Getopt
{
    /** @var string */
    protected $scriptName = '';

    public function __construct($options = null, $defaultType = Getopt::NO_ARGUMENT)
    {
        parent::__construct($options, $defaultType);

        if (isset($_SERVER['argv'][0])) {
            $this->setScriptName($_SERVER['argv'][0]);
        }
    }

    /**
     * Set the scriptName manually.
     *
     * @param $scriptName
     * @return self
     */
    public function setScriptName($scriptName)
    {
        $this->scriptName = $scriptName;
    }

    public function parse($arguments = null)
    {
        if ($arguments === null && isset($_SERVER['argv'])) {
            $arguments = array_slice($_SERVER['argv'], 1);
        } elseif (is_string($arguments)) {
            $arguments = $this->parseArgumentString($arguments);
        }

        parent::parse($arguments);

//        if (count($this->getOperands()) > 0) {
//            $cmd = reset($this->getOperands());
//            // set the command and parse again...
//        }
    }

    public function getHelpText($padding = 25)
    {
        $this->setBanner('Usage: ' . $this->scriptName . " [options] [operands]\n");

        return parent::getHelpText($padding);
    }

    /**
     * Prase the command line string and returns an array.
     *
     * @param string $argsString
     * @return array
     */
    protected function parseArgumentString($argsString)
    {
        $argv = [''];
        $argsString = trim($argsString);
        $argc = 0;

        $state = 'n'; // states: n (normal), d (double quoted), s(single quoted)
        for ($i = 0; $i < strlen($argsString); $i++) {
            $char = $argsString{$i};
            switch ($state) {
                case 'n':
                    if ($char === '\'') {
                        $state = 's';
                    } elseif ($char === '"') {
                        $state = 'd';
                    } elseif (in_array($char, ["\n", "\t", ' '])) {
                        $argc++;
                        $argv[$argc] = '';
                    } else {
                        $argv[$argc] .= $char;
                    }
                    break;

                case 's':
                    if ($char === '\'') {
                        $state = 'n';
                    } else {
                        $argv[$argc] .= $char;
                    }
                    break;

                case 'd':
                    if ($char === '"') {
                        $state = 'n';
                    } else {
                        $argv[$argc] .= $char;
                    }
                    break;
            }
        }

        return array_filter($argv);
    }
}
