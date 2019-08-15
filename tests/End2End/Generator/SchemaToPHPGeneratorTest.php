<?php

declare(strict_types=1);

namespace Synatos\PortaTest\End2End\Generator;

use Codeception\Test\Unit;
use Synatos\Porta\Generator\SchemaToPHPGenerator;
use Synatos\Porta\Model\Schema;

class SchemaToPHPGeneratorTest extends Unit
{


    public function testSchemaToPHP()
    {
        $schema = $this->getSchema();
        $generator = new SchemaToPHPGenerator("Synatos\PortaTest", __DIR__ . '/../../');

        $generator->generateSchema("Synatos\PortaTest\Generated", "SchemaGenTest", $schema);
    }


    /**
     * @return Schema
     */
    private function getSchema(): Schema
    {
        $schemaFile = file_get_contents(__DIR__ . '/Asset/gen.schema.json');

        $schemaArray = json_decode($schemaFile, true);

        $schema = new Schema();
        $schema->fromArray($schemaArray);

        return $schema;

    }
}