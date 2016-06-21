<?php
namespace Amneale\CssValidator;

class Message
{
    /**
     * @var string
     */
    protected $source;

    /**
     * @var int|null
     */
    protected $line;

    /**
     * @var string
     */
    protected $context;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $defaults = [
        'line' => null,
    ];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $attributes = array_merge($this->defaults, $attributes);

        $this->source = $attributes['source'];
        $this->line = $attributes['line'];
        $this->context = $attributes['context'];
        $this->type = $attributes['type'];
        $this->message = $attributes['message'];
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
