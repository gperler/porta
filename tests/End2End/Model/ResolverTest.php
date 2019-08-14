<?php

declare(strict_types=1);

namespace Synatos\PortaTest\Model;

use Codeception\Test\Unit;
use Codeception\Util\Debug;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Model\OpenAPI;
use Synatos\Porta\Model\Reference;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Reference\DefaultReferenceResolver;

class ResolverTest extends Unit
{


    /**
     * @throws InvalidReferenceException
     */
    public function testSchemaReference()
    {
        $openAPI = $this->loadOpenAPI(__DIR__ . '/Schema/petstore-expanded.json');

        $reference = new Reference("#/components/schemas/Pet");

        $array = $openAPI->resolveLocalReference($reference);
        $this->assertNotNull($array);

        Debug::debug(json_encode($array, JSON_PRETTY_PRINT));


        // test resolver
        $defaultResolver = new DefaultReferenceResolver();
        $defaultResolver->setOpenAPI($openAPI);

        $schema = new Schema();
        $schema->setRef("#/components/schemas/Pet");
        $schema = $defaultResolver->resolveSchema($schema);


        $this->assertNotNull($schema);
        $this->assertEquals($array, $schema->jsonSerialize());
    }


    /**
     * @param string $fileName
     *
     * @return OpenAPI
     */
    private function loadOpenAPI(string $fileName): OpenAPI
    {
        $fileContent = file_get_contents($fileName);
        $array = json_decode($fileContent, true);

        $openAPI = new OpenAPI();
        $openAPI->fromArray($array);

        return $openAPI;
    }


}