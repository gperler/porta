<?php

namespace Example;

use Synatos\Porta\Http\ContentType;
use Synatos\Porta\Model\MediaType;
use Synatos\Porta\Model\OpenAPI;
use Synatos\Porta\Model\Operation;
use Synatos\Porta\Model\PathItem;
use Synatos\Porta\Model\RequestBody;
use Synatos\Porta\Model\Schema;

require_once '../vendor/autoload.php';


$intSchema = new Schema();
$intSchema->setType(Schema::TYPE_INTEGER);
$intSchema->setNullable(false);

$objectSchema = new Schema();
$objectSchema->setNullable(true);
$objectSchema->setProperties([
    "intProperty" => $intSchema
]);

$requestMediaType = new MediaType();
$requestMediaType->setSchema($objectSchema);

$requestBody = new RequestBody();
$requestBody->setRequired(true);
$requestBody->setContent([
    ContentType::APPLICATION_JSON => $requestMediaType
]);


$operation = new Operation();
$operation->setRequestBody($requestBody);

$pathItem = new PathItem();
$pathItem->setOperationByMethod(PathItem::METHOD_POST, $operation);

$openAPI = new OpenAPI();
$openAPI->setPaths([
    "/api/do/something" => $pathItem
]);