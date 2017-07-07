<?php

namespace AJT\Toggl;

use Guzzle\Service\Loader\JsonLoader;
use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use Symfony\Component\Config\FileLocator;

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
     * @param array $config Configuration data
     * @return TogglClient
     * @throws \Exception
     */
    public static function factory($config = [])
    {

        $clientConfig = self::getClientConfig($config);

        $guzzleClient = new Client($clientConfig);

        if (isset($config['apiVersion']) && $config['apiVersion'] !== 'v8') {
            throw new \Exception('Only v8 is supported at this time');

        }

        $description = self::getAPIDescriptionByJsonFile('services_v8.json');
        $client = new GuzzleClient($guzzleClient, $description);

        return $client;
    }

    protected static function getAPIDescriptionByJsonFile($file)
    {
        $configDirectories = [__DIR__];
        $locator = new FileLocator($configDirectories);

        $jsonLoader = new JsonLoader($locator);

        $description = $jsonLoader->load($locator->locate($file));
        $description = new Description($description);

        return $description;
    }

    protected static function getClientConfig($config)
    {

        $clientConfig = [];
        if (isset($config['api_key'])) {
            $clientConfig['auth'] = [
                $config['api_key'],
                'api_token',
            ];

        } elseif (isset($config['username'])) {
            if (!isset($config['password'])) {
                $config['password'] = 'api_token';
            }

            $clientConfig['auth'] = [
                $config['username'],
                $config['password'],
            ];

        } else {
            throw new \Exception('Provide authentication details');
        }

        if (isset($config['debug']) && is_bool($config['debug'])) {
            $clientConfig['debug'] = $config['debug'];
        }

        return $clientConfig;

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