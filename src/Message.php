<?php
namespace Amneale\CssValidator;

class Message
{
    /**
     * @var string
     */
    protected $messageType;

    /**
     * @var string
     */
    protected $source;

    /**
     * @var int|null
     */
    protected $line;

    /**
     * @var string|null
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
        'context' => null,
    ];

    /**
     * @param string $messageType
     * @param array $attributes
     */
    public function __construct($messageType, array $attributes)
    {
        $attributes = array_merge($this->defaults, $attributes);

        $this->messageType = $messageType;
        $this->source = $attributes['source'];
        $this->line = $attributes['line'];
        $this->context = $attributes['context'];
        $this->type = $attributes['type'];
        $this->message = $attributes['message'];
    }

    /**
     * @return string
     */
    public function getMessageType()
    {
        return $this->messageType;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return int|null
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @return string|null
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
