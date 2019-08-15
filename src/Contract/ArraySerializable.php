<?php

declare(strict_types=1);

namespace Synatos\Porta\Contract;

use JsonSerializable;

interface ArraySerializable extends JsonSerializable
{
    public function fromArray(array $data);
}