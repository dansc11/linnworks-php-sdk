# PHP SDK for Linnworks API

## Installation
### Composer
Update composer.json to add the package's repository:
```json
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:dansc11/linnworks-php-sdk.git"
    }
]
```

Then install the package:
```shell script
composer require danscott/linnworks-php-sdk 
```

## Authentication
Authorise an application with a user's token using the `Auth/AuthorizeByApplication` endpoint.
```php
$auth = new Auth;

$auth->applicationId = "yourapplicationid";
$auth->applicationSecret = "yourapplicationsecret";
$auth->token = "usertoken";

$response = $auth->authorizeByApplication();
``` 
The session token and user's local API server can be found in the response object.
```php
$token = $response->token;
$server = $response->server;
```

## Making an authenticated request
After authorising the application, add the session token and server when instantiating a resource class to make an authenticated request.

Any subsequent requests using this class will contain authorisation headers. 
```php
$inventory = new Inventory($server, $token);
$inventory->getInventoryItemsCount();
``` 

## Resource Classes

Each resource type, as listed in the [Linnworks API documentation](https://apps.linnworks.net/Api) (i.e. `Auth`, `Customer`, `Dashboards` etc),
has its own class. To make a request to a resource's endpoint, create a resource object
and call the final URI segment as a method on the class.

Parameters can be added to the request as properties of the resource object.

For example, to make a request to [`/Orders/GetOrder`](https://apps.linnworks.net/Api/Method/Orders-GetOrder)
to search for order ID 12345:

```php
$orders = new Orders($server, $token);
$orders->orderId = 12345;
$order = $orders->GetOrder();
``` 