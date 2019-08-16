<?php

namespace Example;

use Synatos\Porta\Generator\SchemaToPHPGenerator;
use Synatos\Porta\Model\Schema;

require_once '../vendor/autoload.php';

$schemaContent = json_decode(file_get_contents(__DIR__ . '/request-schema.json'), true);

$schema = new Schema();
$schema->fromArray($schemaContent);

$psrPrefix = "";
$baseDir = __DIR__;
$namespace = "Example";
$className = "UserRequest";

$generator = new SchemaToPHPGenerator($psrPrefix, $baseDir);
$generator->generateSchema($namespace, $className, $schema);