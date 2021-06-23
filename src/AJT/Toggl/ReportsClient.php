<?php

namespace AJT\Toggl;

use GuzzleHttp\Client;

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
    public static function factory(array $config = [])
    {
        $guzzleClient = new Client(self::getClientConfig($config));

        if (isset($config['apiVersion']) && $config['apiVersion'] !== 'v2') {
            throw new InvalidApiVersionException('Only v2 of the reporting api is supported at this time');
        }

        $description = self::getAPIDescriptionByJsonFile('reporting_v2.json');
        return new self($guzzleClient, $description);
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
        return parent::__call(ucfirst($method), $args);
    }
}