guzzle-toggl
============

A Toggl API client based on Guzzle PHP

## Features

* supports version 9 API with API Key authentication
* supports Toggl Report Api v2 
* based on guzzle 7 

## Installation

The library is available through Composer, so it's easy to get it. 
Simply run this to install it:

    composer require ajt/guzzle-toggl

## Usage
    
To use the Toggl API Client simply instantiate the client with the api key.
More information on the key and authentication available at https://engineering.toggl.com/docs/authentication

```php
<?php

require __DIR__.'/../vendor/autoload.php';

use AJT\Toggl\TogglClient;
$toggl_token = ''; // Fill in your token here
$toggl_client = TogglClient::factory(['api_key' => $toggl_token]);

// if you want to see what is happening, add debug => true to the factory call
$toggl_client = TogglClient::factory(['api_key' => $toggl_token, 'debug' => true]); 
```

Invoke Commands using our `__call` method (auto-complete phpDocs are included)

```php
<?php 
use AJT\Toggl\TogglClient;
$toggl_client = TogglClient::factory(['api_key' => $toggl_token]);

$workspaces = $toggl_client->getWorkspaces([]);

foreach($workspaces as $workspace){
	$id = $workspace['id'];
	print $workspace['name'] . "\n";
}
``` 

Or Use the `getCommand` method (in this case you need to work with the $response['data'] array:

```php
<?php 
use AJT\Toggl\TogglClient;
$toggl_client = TogglClient::factory(['api_key' => $toggl_token]);

//Retrieve the Command from Guzzle
$command = $toggl_client->getCommand('GetWorkspaces', []);
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

## Migrating to v9
Almost all the methods retain the same naming, but some parameters have changed.

The following endpoints now require a `workspace_id` to be passed in the parameters:
- CreateClient
- GetClient
- UpdateClient
- DeleteClient
- CreateProject
- GetProject
- UpdateProject
- CreateProjectUser
- CreateProjectUsers
- UpdateProjectUser
- UpdateProjectUsers
- DeleteProjectUser
- DeleteProjectUsers
- CreateTag
- UpdateTag
- DeleteTag
- CreateTask (also requires a `project_id`)
- GetTask (also requires a `project_id`)
- UpdateTask (also requires a `project_id`)
- UpdateTasks (also requires a `project_id`)
- DeleteTask (also requires a `project_id`)
- DeleteTasks (also requires a `project_id`)
- StartTimeEntry
- StopTimeEntry
- UpdateTimeEntry
- DeleteTimeEntry

The following endpoints now require a `project_id` to be passed in the parameters:
- CreateTask
- GetTask
- UpdateTask
- UpdateTasks
- DeleteTask
- DeleteTasks

The following endpoints are new:
- ArchiveClient
- RestoreClient

The following endpoints have changed their parameters:
- GetProjects (`id` is now `workspace_id`, for clarity)
- GetProjectUsers no longer accepts a `project_id` parameter, but instead accepts a `workspace_id` parameter

The following endpoints have had their name changed, to match the toggl docs more closely:
- InviteWorkspaceUser -> InviteOrganizationUser

The following endpoints have been removed:
- GetWorkspaceWorkspaceUsers
- GetWorkspaceProjects (use GetProjects instead)

## Todo

- [ ] Add some more examples
- [ ] Add tests
- [ ] Add some Response models

## Contributions welcome

Found a bug, open an issue, preferably with the debug output and what you did. 
Bugfix? Open a Pull Request and I'll look into it. 

## Contributors:
Thank you to several contributors over the years to keep this updated with toggl's api versions.
See the contributor page for all of them https://github.com/arendjantetteroo/guzzle-toggl/graphs/contributors

## License

The Toggl API client is available under an MIT License.
