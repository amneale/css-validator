<?php
namespace Amneale\CssValidator\Tests;

use Amneale\CssValidator\Message;
use PHPUnit_Framework_TestCase;

class MessageTest extends PHPUnit_Framework_TestCase
{
    public function testConstruction()
    {
        $message = new Message(
            'warning',
            [
                'source' => 'test.com',
                'line' => 4,
                'context' => '.test',
                'type' => 'at-rule',
                'message' => 'test message',
            ]
        );
        $this->assertInstanceOf(Message::class, $message);
    }
}
