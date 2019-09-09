<?php

declare(strict_types=1);

namespace Synatos\PortaTest\End2End\Generator;

use Synatos\Porta\Contract\ReferenceClassResolver;
use Synatos\Porta\Exception\InvalidReferenceException;

class ReferenceClassTestResolver implements ReferenceClassResolver
{
    /**
     * @param string $reference
     *
     * @return string
     * @throws InvalidReferenceException
     */
    public function getClassNameForReference(string $reference): string
    {
        throw new InvalidReferenceException("");
    }


    /**
     * @param string $reference
     *
     * @return bool
     * @throws InvalidReferenceException
     */
    public function isReferenceNullable(string $reference): bool
    {
        throw new InvalidReferenceException("");
    }


}