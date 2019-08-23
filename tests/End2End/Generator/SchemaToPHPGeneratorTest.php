<?php

declare(strict_types=1);

namespace Synatos\PortaTest\End2End\Generator;

use Codeception\Test\Unit;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Generator\SchemaToPHPGenerator;
use Synatos\Porta\Model\Schema;
use Synatos\PortaTest\Generated\SchemaArrayObjectAdditional;
use Synatos\PortaTest\Generated\SchemaArrayPrimitiveAdditional;
use Synatos\PortaTest\Generated\SchemaNoAdditional;
use Synatos\PortaTest\Generated\SchemaObjectAdditional;
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
     * @throws InvalidReferenceException
     */
    public function testSchemaNoAdditionalProperties()
    {
        $object = $this->getSchemaNoAdditional();
        $this->assertNotNull($object);
        $object->fromArray(self::ARRAY_BASE);
        $this->assertRoundTripEqual(self::ARRAY_BASE, $object->jsonSerialize());
    }


    /**
     * @return SchemaNoAdditional
     * @throws InvalidReferenceException
     */
    private function getSchemaNoAdditional(): SchemaNoAdditional
    {
        return $this->generateSchema("gen.schema-no-additional.json", "SchemaNoAdditional");
    }


    /**
     *
     * @throws InvalidReferenceException
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
     * @return SchemaSimpleAdditional
     * @throws InvalidReferenceException
     */
    private function getSchemaSimpleAdditional(): SchemaSimpleAdditional
    {
        return $this->generateSchema("gen.schema-simple-additional.json", "SchemaSimpleAdditional");
    }


    /**
     *
     * @throws InvalidReferenceException
     */
    public function testSchemaObjectAdditional()
    {
        $array = array_merge(self::ARRAY_BASE, [
            "additional_1" => null,
            "additional_2" => [
                "x1" => "x"
            ],
            "additional_3" => [
                "x1" => "y"
            ]
        ]);

        $object = $this->getSchemaObjectAdditionalProperties();
        $this->assertNotNull($object);
        $object->fromArray($array);
        $this->assertRoundTripEqual($array, $object->jsonSerialize());
    }


    /**
     * @return SchemaObjectAdditional
     * @throws InvalidReferenceException
     */
    private function getSchemaObjectAdditionalProperties(): SchemaObjectAdditional
    {
        return $this->generateSchema("gen.schema-object-additional.json", "SchemaObjectAdditional");
    }


    /**
     *
     * @throws InvalidReferenceException
     */
    public function testSchemaArrayPrimitiveAdditional()
    {
        $array = array_merge(self::ARRAY_BASE, [
            "additional_1" => null,
            "additional_2" => [
                "1", "2", "3"
            ],
            "additional_3" => [
                "x", "y", "z"
            ]
        ]);

        $object = $this->getSchemaArrayPrimitiveAdditionalProperties();
        $this->assertNotNull($object);
        $object->fromArray($array);
        $this->assertRoundTripEqual($array, $object->jsonSerialize());
    }


    /**
     * @return SchemaArrayPrimitiveAdditional
     * @throws InvalidReferenceException
     */
    public function getSchemaArrayPrimitiveAdditionalProperties(): SchemaArrayPrimitiveAdditional
    {
        return $this->generateSchema("gen.schema-array-primitive-additional.json", "SchemaArrayPrimitiveAdditional");
    }


    /**
     * @throws InvalidReferenceException
     */
    public function testSchemaArrayOfObjectsAdditional()
    {
        $array = array_merge(self::ARRAY_BASE, [
            "additional_1" => null,
            "additional_2" => [
                [
                    "x" => 7
                ],
                [
                    "x" => 9
                ]
            ],
            "additional_3" => [
                [
                    "x" => 1
                ]
            ]
        ]);

        $object = $this->testSchemaArrayObjectAdditionalProperties();
        $this->assertNotNull($object);
        $object->fromArray($array);

        $this->assertRoundTripEqual($array, $object->jsonSerialize());
    }


    /**
     * @return SchemaArrayObjectAdditional
     * @throws InvalidReferenceException
     */
    private function testSchemaArrayObjectAdditionalProperties(): SchemaArrayObjectAdditional
    {
        return $this->generateSchema("gen.schema-array-object-additional.json", "SchemaArrayObjectAdditional");
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


    /**
     * @return SchemaArrayPrimitiveAdditional
     * @throws InvalidReferenceException
     */
    public function getSchemaPrimitiveArrayAdditional(): SchemaArrayPrimitiveAdditional
    {
        return $this->generateSchema("gen.schema-array-primitive-additional.json", "SchemaArrayPrimitiveAdditional");
    }


    /**
     * @param string $fileName
     * @param string $className
     *
     * @return mixed
     * @throws InvalidReferenceException
     */
    private function generateSchema(string $fileName, string $className)
    {
        $schema = $this->getSchema($fileName);

        $generator = new SchemaToPHPGenerator("Synatos\PortaTest", __DIR__ . "/../../", new ReferenceClassTestResolver());
        $generator->generateSchema("Synatos\PortaTest\Generated", $className, $schema);

        /** @noinspection PhpIncludeInspection */
        require_once __DIR__ . "/../../Generated/" . $className . ".php";

        $fqClassName = "Synatos\PortaTest\Generated\\" . $className;

        return new $fqClassName;
    }


    /**
     * @param string $fileName
     *
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