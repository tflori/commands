<?php

namespace tflori\Commands;

use Ulrichsg\Getopt\Option;

class Command
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $shortDescription;
    /** @var string */
    protected $longDescription;

    /** @var Option[] */
    protected $options = [];

    /** @var callable */
    protected $handler;

    /**
     * Command constructor.
     *
     * @param string   $name
     * @param string   $shortDescription
     * @param callable $handler
     * @param string   $longDescription
     * @param array    $options
     */
    public function __construct(
        $name,
        $shortDescription,
        callable $handler,
        $longDescription = '',
        array $options = array()
    ) {
        $this->name             = $name;
        $this->shortDescription = $shortDescription;
        $this->handler          = $handler;
        $this->longDescription  = $longDescription ?: $shortDescription;
        $this->options          = $options;
    }

    /**
     * Add options to this command.
     *
     * @param Option[] $options
     */
    public function addOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @return callable
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->longDescription;
    }

    /**
     * @return Option[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }
}
