<?php

require dirname(__FILE__). '/../apikey.php';

require dirname(__FILE__).'/../vendor/autoload.php';

use AJT\Toggl\TogglClient;

// Get the toggl client with your toggl api key
$toggl_client = TogglClient::factory(array('api_key' => $toggl_api_key, 'apiVersion' => $toggl_api_version));

// Get all workspaces
print "getWorkspaces\n";
$workspaces = $toggl_client->getWorkspaces(array());
foreach($workspaces as $workspace){
	$id = $workspace['id'];
	print $id . " - " . $workspace['name'] . "\n";

	// Get all users in this workspace
	$users = $toggl_client->getWorkspaceUsers(array('id' => $id));
	print "This workspace has " . count($users) . " users\n";
	foreach ($users as $user){
		print $user['id'] . ' - ' . $user['fullname'] . "\n";
	}
	print "\n";
}
