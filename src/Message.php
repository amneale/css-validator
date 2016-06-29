<?php
namespace Amneale\CssValidator;

class Message
{
    /**
     * @var string
     */
    protected $type;

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
    protected $issueType;

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
     * @param string $type
     * @param array $attributes
     */
    public function __construct($type, array $attributes)
    {
        $attributes = array_merge($this->defaults, $attributes);

        $this->type = $type;
        $this->source = $attributes['source'];
        $this->line = $attributes['line'];
        $this->context = $attributes['context'];
        $this->issueType = $attributes['type'];
        $this->message = $attributes['message'];
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
    public function getIssueType()
    {
        return $this->issueType;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
