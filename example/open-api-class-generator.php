<?php

namespace Example;

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
