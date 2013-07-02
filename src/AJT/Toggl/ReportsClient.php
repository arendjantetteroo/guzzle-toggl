<?php

namespace AJT\Toggl;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;
use Guzzle\Plugin\Log\LogPlugin;
use Guzzle\Plugin\CurlAuth\CurlAuthPlugin;

/**
 * A TogglClient
 */
class ReportsClient extends Client
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
     * @param array|Collection $config Configuration data
     *
     * @return ReportsClient
     */
    public static function factory($config = array())
    {
        $default = array(
            'base_url' => 'https://www.toggl.com/reports/api/{apiVersion}',
            'debug' => false,
            'apiVersion' => 'v2'
        );
        $required = array('api_key', 'base_url', 'apiVersion');
        $config = Collection::fromConfig($config, $default, $required);

        $serviceDescriptionFile = __DIR__ . '/reporting_' . $config->get('apiVersion') . '.json';
        $description = ServiceDescription::factory($serviceDescriptionFile);

        $client = new self($config->get('base_url'), $config);

        $client->setDescription($description);

        $client->setDefaultHeaders(array(
            "Content-type" => "application/json",
        ));

        $authPlugin = new CurlAuthPlugin($config->get('api_key'), 'api_token');
        $client->addSubscriber($authPlugin);

        if ($config->get('debug')) {
            $client->addSubscriber(LogPlugin::getDebugPlugin());
        }

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
    public function __call($method, $args = null)
    {
        $commandName = ucfirst($method);

        $result = parent::__call($commandName, $args);

        return $result;
    }
}