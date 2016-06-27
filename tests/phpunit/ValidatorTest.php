<?php
namespace Amneale\CssValidator\Tests;

use Amneale\CssValidator\Validator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class ValidatorTest extends PHPUnit_Framework_TestCase
{
    const TEST_URL = 'https://adam-neale.co.uk/';

    /**
     * @var Validator
     */
    private $validator;

    protected function setUp()
    {
        $this->validator = new Validator();
    }

    public function testConstructionWithDefaultArguments()
    {
        $this->assertInstanceOf(Validator::class, $this->validator);

        $this->assertInstanceOf(Client::class, $this->validator->getClient());
        $this->assertEquals(new Client(['base_uri' => Validator::DEFAULT_URL]), $this->validator->getClient());

        $this->assertEquals(Validator::PROFILE_CSS3, $this->validator->getProfile());
    }

    public function testConstructionWithNonDefaultArguments()
    {
        $validator = new Validator(self::TEST_URL, Validator::PROFILE_CSS3);

        $this->assertInstanceOf(Validator::class, $validator);

        $this->assertInstanceOf(Client::class, $validator->getClient());
        $this->assertEquals(new Client(['base_uri' => self::TEST_URL]), $validator->getClient());

        $this->assertEquals(Validator::PROFILE_CSS3, $validator->getProfile());
    }

    public function testGetAndSetClient()
    {
        $client = new Client(['base_uri' => self::TEST_URL]);

        $this->validator->setClient($client);
        $this->assertEquals($client, $this->validator->getClient());
    }

    public function testGetAndSetProfile()
    {
        $this->validator->setProfile(Validator::PROFILE_CSS3);
        $this->assertEquals(Validator::PROFILE_CSS3, $this->validator->getProfile());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Server responded with HTTP status 500
     */
    public function testValidateUrlWithInvalidStatusCode()
    {
        $this->validator->setClient($this->getClient(new Response(500)));
        $this->validator->validateUrl(self::TEST_URL);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Server did not respond with the expected content-type (application/json)
     */
    public function testValidateUrlWithInvalidContentType()
    {
        $this->validator->setClient($this->getClient(new Response(200, ['Content-Type' => 'invalid/type'])));
        $this->validator->validateUrl(self::TEST_URL);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage json_decode error: unexpected character
     */
    public function testValidateUrlWithInvalidJson()
    {
        $this->validator->setClient($this->getClient($this->getJsonResponse('invalidJson')));
        $messages = $this->validator->validateUrl(self::TEST_URL);

        $this->assertInternalType('array', $messages);
    }

    public function testValidateUrl()
    {
        $errors = [
            [
                'source' => 'test.com',
                'line' => 4,
                'context' => '.test',
                'type' => 'at-rule',
                'message' => 'test message',
            ]
        ];

        $this->validator->setClient($this->getClient(
            $this->getJsonResponse('{"cssvalidation": {"errors": ' . json_encode($errors) . '}}')
        ));
        $messages = $this->validator->validateUrl(self::TEST_URL);

        $this->assertInternalType('array', $messages);
        $this->assertCount(1, $messages);
    }

    /**
     * @param Response $response
     * @return Client|PHPUnit_Framework_MockObject_MockObject
     */
    private function getClient(Response $response)
    {
        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client
            ->expects($this->once())
            ->method('__call')
            ->with($this->equalTo('get'))
            ->willReturn($response);

        return $client;
    }

    /**
     * @param string $json
     * @return Response
     */
    private function getJsonResponse($json)
    {
        $body = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->getMock();

        $body
            ->expects($this->once())
            ->method('__toString')
            ->willReturn($json);

        return new Response(200,  ['Content-Type' => 'application/json'], $body);
    }
}
