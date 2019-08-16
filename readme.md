# Porta

Porta is greek and means door. This project can be used as door for your Rest API endpoints.
The Open API standard allows to describe exactly what information in a rest api is coming in and leaving a server.
The standard can be used for
1.) Description / Documentation of interfaces with tools like swagger
2.) Validation of incoming and outgoing Rest Messages
3.) Generation of Request/Response classes that allow to transfer untyped array/json to typed Objects.

## Description
Porta is an Open API 3.0 Schema Validator for Http Requests.


## Installation

```sh
composer require synatos/porta
```

## Import/Export

```php
use Synatos\Porta\Model\OpenAPI;

$openAPI = new OpenAPI();

# yaml
$openAPI->fromYamlFile(__DIR__ . "/my-open-api.yml");
$openAPI->toYamlFile(__DIR__ . "/export.yml");

# json
$openAPI->fromJSONFile("my-open-api.json");
$openAPI->toJSONFile(__DIR__ . "/export.json");
```

## Validation

```php
<?php
use Synatos\Porta\Http\ContentType;
use Synatos\Porta\Http\HttpHeader;
use Synatos\Porta\Model\OpenAPI;
use Synatos\Porta\Porta;

require_once '../vendor/autoload.php';

$openAPI = new OpenAPI();
$openAPI->fromJSONFile(__DIR__ . '/open-api.json');

$porta = new Porta();
$porta->setOpenAPI($openAPI);

$path = "/api/security/login-with-password";
$method = "POST";
$header = [
    HttpHeader::CONTENT_TYPE => ContentType::APPLICATION_JSON
];
$query = [];

$requestBody = json_encode([
    "email" => "email@email.com",
    "password" => "abcd1234"
]);

$validationMessageList = $porta->validateRequest($path, $method, $header,$query, $requestBody);
```

## Generate PHP Class from Schema


## Generate Open API Class


## Model Manipulation
 
 
 