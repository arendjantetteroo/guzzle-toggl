<?php

require dirname(__FILE__) . '/../apikey.php';

require dirname(__FILE__) . '/../vendor/autoload.php';

use \AJT\Toggl\ReportsClient;

// Get the toggl client with your toggl api key
$toggl_reports = ReportsClient::factory([
    'api_key'    => $toggl_api_key,
    'apiVersion' => $toggle_api_reporting_version,
    'debug'      => false,
]);

// Create a client
print "getWeeklyReport\n";
// manually populate variables to create test client
$user_agent = "Toggl PHP Client";
$wid = 1973711; // Retrieve this with the get-workspaces.php file and update

//
//
//$clientdata = array('client' => array('name' => $client_name, 'wid' => $wid, 'notes' => $client_notes));
$response = $toggl_reports->weekly([
    "user_agent"   => $user_agent,
    "workspace_id" => $wid,
]);

$p = $response['data'];
foreach ($p as $entry) {

    $totalMilliseconds = $entry['totals'][7];
    $totalSeconds = $totalMilliseconds / 1000;

    $hours = floor($totalSeconds / 3600);
    $mins = floor($totalSeconds / 60 % 60);

    echo $entry['title']['client'] . ' - ' . $entry['title']['project'] . ' (' . $hours . ':' . $mins . ')' . PHP_EOL;
}