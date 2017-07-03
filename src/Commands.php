<?php

namespace tflori\Commands;

use Ulrichsg\Getopt\Getopt;

class Commands extends Getopt
{
    /** @var Command[] */
    protected $commands = [];

    /** @var Command */
    protected $command;

    /** @var string */
    protected $banner = ''; // we don't use banner

    /** @var string */
    protected $template = __DIR__ . '/../resources/helpTemplate.php';

    /** @var string */
    protected $description = '';

    public function addCommand(Command $command)
    {
        $this->commands[$command->getName()] = $command;
    }

    public function parse($arguments = null)
    {
        $this->command = null;
        parent::parse($arguments);

        if (count($this->getOperands()) > 0) {
            $cmd = $this->getOperand(0);
            if (isset($this->commands[$cmd])) {
                $arguments = array_slice($this->operands, 1);
                $this->command = $this->commands[$cmd];

                $commandOptions = $this->command->getOptions();
                if (!empty($commandOptions)) {
                    $this->addOptions($commandOptions);
                }

                $commonOptions = $this->options;
                parent::parse($arguments);
                $this->options += $commonOptions;
            }
        }
    }

    public function getHelpText($padding = 25)
    {
        $this->banner = '';

        $scriptName = $this->scriptName;
        $options = parent::getHelpText($padding);
        $hasOptions = !empty($this->optionList);
        if (!$this->command) {
            $description = $this->description;
            $commands = $this->getCommandsHelp($padding);
            $hasCommands = !empty($this->commands);
            $command = '';
        } else {
            $description = $this->command->getDescription();
            $commands = '';
            $hasCommands = false;
            $command = $this->command->getName();
        }

        ob_start();
        include $this->template;
        return ob_get_clean();
    }

    protected function getCommandsHelp($padding)
    {
        $help = '';
        if (!empty($this->commands)) {
            $help .= "Commands:\n";
            foreach ($this->commands as $command) {
                $help .= sprintf(
                    "%s %s\n",
                    str_pad(sprintf('  %s', $command->getName()), $padding),
                    $command->getDescription(true)
                );
            }
        }
        return $help;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return Command
     */
    public function getCommand()
    {
        return $this->command;
    }
}
