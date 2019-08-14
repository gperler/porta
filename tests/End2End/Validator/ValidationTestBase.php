<?php

declare(strict_types=1);

namespace Synatos\PortaTest\Validator;

use Codeception\Test\Unit;
use Codeception\Util\Debug;
use Synatos\Porta\Contract\Validator;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;

class ValidationTestBase extends Unit
{
    /**
     * @var bool
     */
    protected $debug;

    /**
     * @param Validator $validator
     * @param Schema $schema
     * @param $value
     *
     * @throws InvalidSchemaExceptionException
     */
    protected function testSuccess(Validator $validator, Schema $schema, $value)
    {
        $validationMessageList = $validator->validate($schema, $value, []);
        if ($this->debug) {
            Debug::debug($validationMessageList);
        }
        $this->assertCount(0, $validationMessageList);
    }


    /**
     * @param Validator $validator
     * @param Schema $schema
     * @param $value
     * @param string $code
     *
     * @throws InvalidSchemaExceptionException
     */
    protected function testFail(Validator $validator, Schema $schema, $value, string $code)
    {
        $validationMessageList = $validator->validate($schema, $value, ["property"]);
        $this->assertCount(1, $validationMessageList);
        if ($this->debug) {
            Debug::debug($validationMessageList);
        }
        $validationMessage = $validationMessageList[0];
        $this->assertSame($code, $validationMessage->getCode());
    }
}