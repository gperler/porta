<?php

namespace Example;

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

$requestBody = json_encode([
    "user" => "email@email.com",
    "password" => "abcd1234"
]);
$validationMessageList = $porta->validateRequest($path, $method, $header,$query, $requestBody);

echo json_encode($validationMessageList, JSON_PRETTY_PRINT);