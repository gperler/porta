<?php

declare(strict_types=1);

namespace Synatos\PortaTest\Model;

use Codeception\Test\Unit;
use Synatos\Porta\Model\OpenAPI;

class ModelTest extends Unit
{

    /**
     *
     */
    public function testParseAndBack()
    {
        $this->compareJSONFile(__DIR__ . '/Schema/callback-example.json');
        $this->compareJSONFile(__DIR__ . '/Schema/link-example.json');
        $this->compareJSONFile(__DIR__ . '/Schema/open-api.json');
        $this->compareJSONFile(__DIR__ . '/Schema/petstore.json');
        $this->compareJSONFile(__DIR__ . '/Schema/petstore-expanded.json');
        $this->compareJSONFile(__DIR__ . '/Schema/uspto.json');
    }


    /**
     * @param string $fileName
     */
    private function compareJSONFile(string $fileName)
    {
        $fileContent = file_get_contents($fileName);
        $array = json_decode($fileContent, true);

        $openAPI = new OpenAPI();
        $openAPI->fromArray($array);

        $reparsed = $openAPI->jsonSerialize();
        $this->assertEquals($array, $reparsed);
    }


}