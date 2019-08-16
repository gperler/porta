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

You can easily import export JSON/Yaml Files

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

### Request Validation

Request can easily be validated against a given schema. Important: make sure
the content type for the request is set in the http header. Open API allows
several artifacts to be validated: 
* Query Parameter 
* Route Parameter
* Http Header
* Cookies
* Request Body

The example below shows how a http request is validated against the open API Schema.

```php
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

Schemas get easily get larger and parsing json/yaml including the file load can become time consuming.
Therefore an existing schema can be compiled into a class the has the schema as array inline

```php
use Synatos\Porta\Generator\OpenAPIClassGenerator;
use Synatos\Porta\Model\OpenAPI;

require_once '../vendor/autoload.php';

$openAPI = new OpenAPI();
$openAPI->fromJSONFile(__DIR__ . '/open-api.json');

$fullyQualifiedClassName = "Example\\CompiledSchema";
$psrPrefix = "";
$baseDir = __DIR__;

$openAPIClassGenerator = new OpenAPIClassGenerator();
$openAPIClassGenerator->generate($openAPI, $fullyQualifiedClassName, $psrPrefix, $baseDir);
```

This compile a class with the following content:
```php
class CompiledSchema
{

    /**
     * @var OpenAPI
     */
    private static $openAPI;

    /**
     * 
     * @return OpenAPI
     */
    public static function getOpenAPI() : OpenAPI
    {
        if (self::$openAPI === null) {
            self::$openAPI = new OpenAPI();
            self::$openAPI->fromArray([ /* schema will be compiled into an array */]);
        }
        return self::$openAPI;
    }
}

```


## Generate Open API Class

You can generate for a given schema a class that will contain all the typed properties. Futhermore two method 
<code>\JsonSerializable</code> and <code>fromArray(array $data)</code> allow to initialize from array
 and generated an array again. This way you do not need to access
arrays anymore but can create the Schema object from an array and work fully typed with
the object.

```php
$schemaContent = json_decode(file_get_contents(__DIR__ . '/request-schema.json'), true);

$schema = new Schema();
$schema->fromArray($schemaContent);

$psrPrefix = "";
$baseDir = __DIR__;
$namespace = "Example";
$className = "UserRequest";

$generator = new SchemaToPHPGenerator($psrPrefix, $baseDir);
$generator->generateSchema($namespace, $className, $schema);

// see example/compile-schema-class.php
```


The generated class will look like this 
```php
class UserRequest implements \JsonSerializable
{

    /**
     * @var string
     */
    protected $id;
    
    /**
     * @param string|null $id
     * 
     * @return void
     */
    public function setId(?string $id)
    {
        $this->id = $id;
    }

    /**
     * 
     * @return string|null
     */
    public function getId() : ?string
    {
        return $this->id;
    }
    
    /**
     * @param array $array
     * 
     * @return void
     */
    public function fromArray(array $array) {/* ... */}
    
    
    /**
     * 
     * @return array
     */
    public function jsonSerialize() : array {/* ... */}

```

## Model Manipulation
Every artifact in Open API has a object representation. So you can easily build up
Schema, Operations or any other part 

```php 
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
```
