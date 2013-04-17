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
     * - api_key: API key
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
            'apiVersion' => 'v6'
        );
        $required = array('api_key', 'base_url','apiVersion');
        $config = Collection::fromConfig($config, $default, $required);

        $client = new self($config->get('base_url'), $config);
        // Attach a service description to the client
        if($config->get('apiVersion') == 'v8'){
            $description = ServiceDescription::factory(__DIR__ . '/services_v8.json');    
        } else {
            $description = ServiceDescription::factory(__DIR__ . '/services_v6.json');    
        }
        
        $client->setDescription($description);

		$client->setDefaultHeaders(array(
			"Content-type" => "application/json",
		));
		
		$authPlugin = new CurlAuthPlugin($config->get('api_key'), 'api_token');
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
        if (isset($result['data'])) {
        	return $result['data'];
        }
        return $result;
    }
}