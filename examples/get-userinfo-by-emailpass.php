<?php

require __DIR__ . '/../apikey.php';

require __DIR__ .'/../vendor/autoload.php';

use AJT\Toggl\TogglClient;

// Get the toggl client with your toggl username and password
$toggl_client = TogglClient::factory(array('username' => $username, 'password' => $password, 'apiVersion' => $toggl_api_version, 'debug' => true));

// Get the current user
$currentUser = $toggl_client->GetCurrentUser();

var_dump($currentUser);