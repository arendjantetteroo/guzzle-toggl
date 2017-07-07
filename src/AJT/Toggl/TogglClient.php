<?php

namespace AJT\Toggl;

use Guzzle\Service\Loader\JsonLoader;
use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use Symfony\Component\Config\FileLocator;

//use Guzzle\Common\Collection;
//use Guzzle\Service\Client;
//use Guzzle\Service\Description\ServiceDescription;
//use Guzzle\Plugin\Log\LogPlugin;
//use Guzzle\Plugin\CurlAuth\CurlAuthPlugin;

/**
 * A TogglClient
 */
class TogglClient extends GuzzleClient
{

    /**
     * Factory method to create a new TogglClient
     *
     * The following array keys and values are available options:
     * - username: username or API key
     * - password: password (if empty, then username is a API key)
     *
     * See https://www.toggl.com/public/api#api_token for more information on the api token
     *
     * @param array|Collection $config Configuration data
     *
     * @return self
     */
    public static function factory($config = [])
    {
//        $default = [
//            'base_url'   => 'https://www.toggl.com/api/{apiVersion}',
//            'debug'      => false,
//            'apiVersion' => 'v8',
//            'api_key'    => '',
//            'username'   => '',
//            'password'   => '',
//        ];
//        $required = [
//            'api_key',
//            'username',
//            'password',
//            'base_url',
//            'apiVersion',
//        ];
        //  $config = Collection::fromConfig($config, $default, $required);

        $clientConfig = [];
        if (isset($config['api_key'])) {
            $clientConfig['auth'] = [
                $config['api_key'],
                'api_token',
            ];

        }
        if (isset($config['username'])) {
            $clientConfig['auth'] = [
                $config['username'],
                $config['password'],
            ];
        }

        if (isset($config['debug']) && is_bool($config['debug'])) {
            $clientConfig['debug'] = $config['debug'];
        }




        $guzzleClient = new Client($clientConfig);


        // Attach a service description to the client
//        if($config->get('apiVersion') == 'v8'){
//            //$description = ServiceDescription::factory(__DIR__ . '/services_v8.json');
//        } else {
//            die('Only v8 is supported at this time');
//        }








        $configDirectories = [__DIR__];
        $locator = new FileLocator($configDirectories);

        $jsonLoader = new JsonLoader($locator);

        $description = $jsonLoader->load($locator->locate('services_v8.json'));
        $description = new Description($description);
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
        /** @var \GuzzleHttp\Command\Result $result */
        $result = parent::__call($commandName, $args);
        // Remove data field
        if (is_array($result) && isset($result['data'])) {
            return $result['data'];
        }

        return $result;
    }
}