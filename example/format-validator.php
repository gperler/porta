<?php

namespace Example;

use Synatos\Porta\Contract\Validator;
use Synatos\Porta\Http\ContentType;
use Synatos\Porta\Http\HttpHeader;
use Synatos\Porta\Model\OpenAPI;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Porta;
use Synatos\Porta\Validator\FormatValidatorFactory;

require_once '../vendor/autoload.php';

class EmailValidator implements Validator
{
    public function validate(Schema $schema, $value, array $propertyPath): array
    {
        echo "validating " . $value . PHP_EOL;
        return [];
    }
}

FormatValidatorFactory::addFormatValidator("email", new EmailValidator());


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

$validationMessageList = $porta->validateRequest($path, $method, $header, $query, $requestBody);



