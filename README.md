guzzle-toggl
============

A Toggl API client based on Guzzle PHP

## Features

* supports complete version 8 API with API Key authentication (thanks to @dirx)
* supports Toggl Report Api v2 (thanks to @dirx)
* now based on guzzle 6 (thanks to @echron)

## Installation

The library is available through Composer, so its easy to get it. 
Simply run this to install it:

    composer require ajt/guzzle-toggl

## Usage
    
To use the Toggl API Client simply instantiate the client with the api key.
More information on the key and authentication available at https://github.com/toggl/toggl_api_docs/blob/master/chapters/authentication.md

```php
<?php

require dirname(__FILE__).'/../vendor/autoload.php';

use AJT\Toggl\TogglClient;
$toggl_token = ''; // Fill in your token here
$toggl_client = TogglClient::factory(array('api_key' => $toggl_token));

// if you want to see what is happening, add debug => true to the factory call
$toggl_client = TogglClient::factory(array('api_key' => $toggl_token, 'debug' => true)); 
```

Invoke Commands using our `__call` method (auto-complete phpDocs are included)

```php
<?php 

$toggl_client = TogglClient::factory(array('api_key' => $toggl_token));

$workspaces = $toggl_client->getWorkspaces(array());

foreach($workspaces as $workspace){
	$id = $workspace['id'];
	print $workspace['name'] . "\n";
}
``` 

Or Use the `getCommand` method (in this case you need to work with the $response['data'] array:

```php
<?php 

$toggl_client = TogglClient::factory(array('api_key' => $toggl_token));

//Retrieve the Command from Guzzle
$command = $toggl_client->getCommand('GetWorkspaces', array());
$command->prepare();

$response = $command->execute();

$workspaces = $response['data'];

foreach($workspaces as $workspace){
	$id = $workspace['id'];
	print $workspace['name'] . "\n";
}
```

## Examples
Copy the apikey-dist.php to apikey.php (in the root directory) and add your apikey.
Afterwards you can execute the examples in the examples directory. 

You can look at the services.json for details on what methods are available and what parameters are available to call them

## Todo

- [ ] Add some more examples
- [ ] Add tests
- [ ] Add some Response models

## Contributions welcome

Found a bug, open an issue, preferably with the debug output and what you did. 
Bugfix? Open a Pull Request and i'll look into it. 

## License

The Toggl API client is available under an MIT License.
