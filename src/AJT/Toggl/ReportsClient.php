<?php

namespace AJT\Toggl;

use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\GuzzleClient;

/**
 * A TogglClient
 */
class ReportsClient extends TogglClient
{

    /**
     * Factory method to create a new TogglClient
     *
     * The following array keys and values are available options:
     * - base_url: Base URL of web service
     * - api_key: API key
     *
     * See https://www.toggl.com/public/api#api_token for more information on the api token
     *
     * @param array $config Configuration data
     * @return ReportsClient
     * @throws \Exception
     */
    public static function factory($config = [])
    {

        $clientConfig = self::getClientConfig($config);

        $guzzleClient = new Client($clientConfig);

        if (isset($config['apiVersion']) && $config['apiVersion'] !== 'v2') {
            throw new \Exception('Only v8 is supported at this time');

        }

        $description = self::getAPIDescriptionByJsonFile('reporting_v2.json');
        $client = new GuzzleClient($guzzleClient, $description);

        return $client;
    }

    /**
     * Shortcut for executing Commands in the Definitions.
     *
     * @param string $method
     * @param array|null $args
     *
     * @return mixed|void
     *
     */
    public function __call($method, array $args)
    {
        $commandName = ucfirst($method);

        $result = parent::__call($commandName, $args);

        return $result;
    }
}