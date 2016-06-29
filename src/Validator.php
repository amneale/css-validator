<?php
namespace Amneale\CssValidator;

use GuzzleHttp\Client;
use RuntimeException;
use function GuzzleHttp\json_decode;

class Validator
{
    const DEFAULT_URL = 'https://jigsaw.w3.org/css-validator/validator';

    const PROFILE_CSS1     = 'css1';
    const PROFILE_CSS2     = 'css2';
    const PROFILE_CSS21    = 'css21';
    const PROFILE_CSS3     = 'css3';
    const PROFILE_SVG      = 'svg';
    const PROFILE_SVGBASIC = 'svgbasic';
    const PROFILE_SVGTINY  = 'svgtiny';
    const PROFILE_MOBILE   = 'mobile';
    const PROFILE_ATSCTV   = 'atsc-tv';
    const PROFILE_TV       = 'tv';
    const PROFILE_NONE     = 'none';

    const WARNING_LEVEL_NONE = 'no';
    const WARNING_LEVEL_LESS = '0';
    const WARNING_LEVEL_MORE = '1';
    const WARNING_LEVEL_FULL = '2';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $profile;

    /**
     * @var string
     */
    private $warningLevel;

    /**
     * @param string $validatorUrl
     * @param string $profile
     * @param string $warningLevel
     */
    public function __construct(
        $validatorUrl = self::DEFAULT_URL,
        $profile = self::PROFILE_CSS3,
        $warningLevel = self::WARNING_LEVEL_FULL
    ) {
        $this->setClient(new Client(['base_uri' => $validatorUrl]));
        $this->setProfile($profile);
        $this->setWarningLevel($warningLevel);
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param string $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    /**
     * @return string
     */
    public function getWarningLevel()
    {
        return $this->warningLevel;
    }

    /**
     * @param string $warningLevel
     */
    public function setWarningLevel($warningLevel)
    {
        $this->warningLevel = $warningLevel;
    }

    /**
     * @param $url
     * @return Message[]
     * @throws RuntimeException
     */
    public function validateUrl($url)
    {
        $response = $this->client->get(
            '',
            [
                'query' => [
                    'uri' => $url,
                    'profile' => $this->profile,
                    'warning' => $this->warningLevel,
                    'output' => 'json',
                ]
            ]
        );

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException('Server responded with HTTP status ' . $response->getStatusCode());
        }

        if (strpos($response->getHeaderLine('Content-Type'), 'application/json') === false) {
            throw new RuntimeException('Server did not respond with the expected content-type (application/json)');
        }

        $messages = [];
        $result = json_decode($response->getBody(), true);

        if (isset($result['cssvalidation']['errors'])) {
            foreach ($result['cssvalidation']['errors'] as $messageAttributes) {
                $messages[] = new Message('error', $messageAttributes);
            }
        }

        if (isset($result['cssvalidation']['warnings'])) {
            foreach ($result['cssvalidation']['warnings'] as $messageAttributes) {
                $warnings[] = new Message('warning', $messageAttributes);
            }
        }

        return $messages;
    }
}
