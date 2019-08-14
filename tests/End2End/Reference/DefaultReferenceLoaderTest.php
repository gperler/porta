<?php

declare(strict_types=1);

namespace Synatos\PortaTest\Reference;

use Codeception\Test\Unit;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Model\Reference;
use Synatos\Porta\Reference\DefaultReferenceLoader;

class DefaultReferenceLoaderTest extends Unit
{

    public function testReferenceLoader()
    {
        $defaultReferenceLoader = new DefaultReferenceLoader();

        $reference = new Reference("Asset/valid-json.json");

        try {
            $defaultReferenceLoader->loadReference($reference);
            $this->assertFalse(true);
        } catch (InvalidReferenceException $e) {
        }

        // load file
        $defaultReferenceLoader->setBasePath(__DIR__);
        $data = $defaultReferenceLoader->loadReference($reference);
        $this->assertNotNull($data);
        $this->assertEquals([
            "Entity" => [
                "value" => true
            ]
        ], $data);

        // load with local
        $reference = new Reference("Asset/valid-json.json#/Entity");
        $data = $defaultReferenceLoader->loadReference($reference);
        $this->assertNotNull($data);
        $this->assertEquals([
            "value" => true
        ], $data);

        $reference = new Reference("Asset/empty-file.txt");
        try {
            $defaultReferenceLoader->loadReference($reference);
            $this->assertFalse(true);
        } catch (InvalidReferenceException $e) {
        }
    }


}