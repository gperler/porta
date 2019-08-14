<?php

declare(strict_types=1);

namespace Synatos\Porta\Helper;

class ArrayHelper
{

    /**
     * @param array $array
     * @return array
     */
    public static function filterEmpty(array $array): array
    {
        return array_filter($array, function ($item) {
            return $item !== null;
        });
    }

}