<?php

require dirname(__FILE__). '/../apikey.php';

require dirname(__FILE__).'/../vendor/autoload.php';

use AJT\Toggl\TogglClient;

// Get the toggl client with your toggl api key
$toggl_client = TogglClient::factory(array('api_key' => $toggl_api_key, 'apiVersion' => $toggl_api_version, 'debug' => true));

// Create a client
print "createClient\n";
// manually populate variables to create test client
$client_name = "My Toggl Test client";
$wid = 807286; // Retrieve this with the get-workspaces.php file and update
$client_notes = "11";

echo "What should post is: $client_name - $wid - $client_notes";
$clientdata = array('client' => array('name' => $client_name, 'wid' => $wid, 'notes' => $client_notes));
$response = $toggl_client->createClient($clientdata);