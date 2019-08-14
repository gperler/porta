<?php

declare(strict_types=1);

namespace Synatos\Porta\Helper;

class TypedArrayAccessor
{

    protected $valueList;


    /**
     * TypedArrayAccessor constructor.
     * @param array $valueList
     */
    public function __construct(array $valueList)
    {
        $this->valueList = $valueList;
    }



}