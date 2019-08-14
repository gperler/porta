<?php

declare(strict_types=1);

namespace Synatos\Porta\Contract;

use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Model\Reference;

interface ReferenceLoader
{

    /**
     * @param Reference $reference
     *
     * @return array
     * @throws InvalidReferenceException
     */
    public function loadReference(Reference $reference): ?array;


    /**
     * @param string $basePath
     */
    public function setBasePath(string $basePath): void;

}