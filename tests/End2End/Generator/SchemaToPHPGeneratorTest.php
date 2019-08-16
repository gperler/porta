<?php

declare(strict_types=1);

namespace Synatos\PortaTest\End2End\Generator;

use Codeception\Test\Unit;
use Synatos\Porta\Generator\SchemaToPHPGenerator;
use Synatos\Porta\Model\Schema;
use Synatos\PortaTest\Generated\SchemaNoAdditional;
use Synatos\PortaTest\Generated\SchemaSimpleAdditional;

class SchemaToPHPGeneratorTest extends Unit
{

    const ARRAY_BASE = [
        "bool" => true,
        "int" => 3,
        "float" => 19.08,
        "string" => "Hello",
        "boolNullable" => false,
        "intNullable" => 9,
        "floatNullable" => null,
        "stringNullable" => "string",
        "enumValue" => "ASC",
        "simpleObject" => [
            "prop" => true,
        ],
        "primitiveArray" => [
            true, false, true
        ],
        "objectArray" => [
            [
                "x" => 77,
                "y" => 19
            ]
        ]

    ];


    /**
     *
     */
    public function testSchemaNoAdditionalProperties()
    {
        $object = $this->getSchemaNoAdditional();
        $this->assertNotNull($object);
        $object->fromArray(self::ARRAY_BASE);
        $this->assertRoundTripEqual(self::ARRAY_BASE, $object->jsonSerialize());
    }

    /**
     *
     */
    public function testSchemaSimpleAdditional()
    {
        $array = array_merge(self::ARRAY_BASE, [
            "additional1" => null,
            "additional2" => 0,
            "additional3" => 77
        ]);



        $object = $this->getSchemaSimpleAdditional();
        $this->assertNotNull($object);
        $object->fromArray($array);
        $this->assertRoundTripEqual($array, $object->jsonSerialize());
    }


    /**
     * @param array $original
     * @param array $readyForJSON
     */
    private function assertRoundTripEqual(array $original, array $readyForJSON)
    {
        $json = json_decode(json_encode($readyForJSON), true);
        $this->assertEquals($original, $json);
    }


    public function testSchemaObjectAdditionalProperties()
    {
        $this->generateSchema("gen.schema-object-additional.json", "SchemaObjectAdditional");

    }

    public function testSchemaArrayPrimitiveAdditionalProperties()
    {
        $this->generateSchema("gen.schema-array-primitive-additional.json", "SchemaArrayPrimitiveAdditional");
    }

    public function testSchemaArrayObjectAdditionalProperties()
    {
        $this->generateSchema("gen.schema-array-object-additional.json", "SchemaArrayObjectAdditional");
    }


    private function getSchemaNoAdditional(): SchemaNoAdditional
    {
        return $this->generateSchema("gen.schema-no-additional.json", "SchemaNoAdditional");
    }

    /**
     * @return SchemaSimpleAdditional
     */
    private function getSchemaSimpleAdditional(): SchemaSimpleAdditional
    {
        return $this->generateSchema("gen.schema-simple-additional.json", "SchemaSimpleAdditional");
    }

    /**
     * @param string $fileName
     * @param string $className
     * @return mixed
     */
    private function generateSchema(string $fileName, string $className)
    {
        $schema = $this->getSchema($fileName);
        $generator = new SchemaToPHPGenerator("Synatos\PortaTest", __DIR__ . "/../../");
        $generator->generateSchema("Synatos\PortaTest\Generated", $className, $schema);

        require_once __DIR__ . "/../../Generated/" . $className . ".php";

        $fqClassName = "Synatos\PortaTest\Generated\\" . $className;

        return new $fqClassName;
    }


    /**
     * @param string $fileName
     * @return Schema
     */
    private function getSchema(string $fileName): Schema
    {
        $schemaFile = file_get_contents(__DIR__ . "/Asset/" . $fileName);

        $schemaArray = json_decode($schemaFile, true);

        $schema = new Schema();
        $schema->fromArray($schemaArray);

        return $schema;

    }
}