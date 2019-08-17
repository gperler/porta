<?php

declare(strict_types=1);

namespace Synatos\Porta\Contract;

use Synatos\Porta\Exception\InvalidReferenceException;

interface ReferenceClassResolver
{

    /**
     * @param string $reference
     *
     * @return string
     * @throws InvalidReferenceException
     */
    public function getClassNameForReference(string $reference): string;
}