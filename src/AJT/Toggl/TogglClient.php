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
class TogglClient extends Client
{

    /**
     * Factory method to create a new TogglClient
     *
     * The following array keys and values are available options:
     * - base_url: Base URL of web service
     * - username: username or API key
     * - password: password (if empty, then username is a API key)
     *
     * See https://www.toggl.com/public/api#api_token for more information on the api token
     *
     * @param array|Collection $config Configuration data
     *
     * @return self
     */
    public static function factory($config = array())
    {
        $default = array(
            'base_url' => 'https://www.toggl.com/api/{apiVersion}',
            'debug' => false,
            'apiVersion' => 'v8',
            'api_key' => '',
            'username' => '',
            'password' => ''
        );
        $required = array('api_key', 'username', 'password', 'base_url','apiVersion');
        $config = Collection::fromConfig($config, $default, $required);

        $client = new self($config->get('base_url'), $config);
        // Attach a service description to the client
        if($config->get('apiVersion') == 'v8'){
            $description = ServiceDescription::factory(__DIR__ . '/services_v8.json');
        } else {
            die('Only v8 is supported at this time');
        }

        $client->setDescription($description);

        $client->setDefaultHeaders(array(
            "Content-type" => "application/json",
        ));

        if(!empty($config->get('api_key'))) {
            $config->set('username', $config->get('api_key'));
            $config->set('password', 'api_token');
        }

        if(empty($config->get('password'))) {
            $config->set('password', 'api_token');
        }
        $authPlugin = new CurlAuthPlugin($config->get('username'), $config->get('password'));
        $client->addSubscriber($authPlugin);

        if($config->get('debug')){
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

        // Remove data field
        if (is_array($result) && isset($result['data'])) {
            return $result['data'];
        }
        return $result;
    }
}