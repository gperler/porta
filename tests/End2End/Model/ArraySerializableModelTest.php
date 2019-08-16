<?php

declare(strict_types=1);

namespace Synatos\PortaTest\End2End\Model;

use Codeception\Test\Unit;
use Synatos\PortaTest\End2End\Model\Asset\MainObject;

class ArraySerializableModelTest extends Unit
{


    public function testSerialize()
    {
        $mainObject = new MainObject();


        $array = [
            '$ref' => "#/components/schema/User",
            "buildIn" => 7,
            "zero" => 0,
            "nestedObject" => [
                "name" => "object"
            ],
            "nestedArrayObject" => [
                [
                    "name" => "array 1"
                ],
                [
                    "name" => "array 2"
                ]
            ],
            "nestedAssociativeArrayObject" => [
                "key1" => [
                    "name" => "key1"
                ],
                "key2" => [
                    "name" => "key2"
                ]
            ]
        ];


        $mainObject->fromArray($array);

        $nestedObject = $mainObject->getNestedObject();
        $this->assertNotNull($nestedObject);
        $this->assertSame("object", $nestedObject->getName());


        $nestedObjectArray = $mainObject->getNestedArrayObject();
        $this->assertCount(2, $nestedObjectArray);
        $this->assertSame("array 2", $nestedObjectArray[1]->getName());


        //
        $nestedAssociativeArray = $mainObject->getNestedAssociativeArrayObject();
        $this->assertTrue(isset($nestedAssociativeArray["key1"]));
        $this->assertTrue(isset($nestedAssociativeArray["key2"]));

        // test reference
        $this->assertSame("#/components/schema/User", $mainObject->getRef());

        $reference = $mainObject->getReference();
        $this->assertNotNull($reference);
        $this->assertSame("#/components/schema/User", $reference->getReference());
        $this->assertTrue($reference->isLocal());
        $this->assertEquals([
            "components",
            "schema",
            "User"
        ], $reference->getLocalPartPartList());


        $this->assertEquals($array, $mainObject->jsonSerialize());
    }


}