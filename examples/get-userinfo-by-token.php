<?php

require __DIR__ . '/../apikey.php';

require __DIR__ .'/../vendor/autoload.php';

use AJT\Toggl\TogglClient;

// Get the toggl client with your toggl api key
$toggl_client = TogglClient::factory(array('api_key' => $toggl_api_key, 'apiVersion' => $toggl_api_version, 'debug' => true));

// Get the current user
$currentUser = $toggl_client->GetCurrentUser();

var_dump($currentUser);