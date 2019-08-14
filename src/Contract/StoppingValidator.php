<?php

declare(strict_types=1);

namespace Synatos\Porta\Contract;

interface StoppingValidator extends Validator
{

    /**
     * @return bool
     */
    public function canContinueOnValidationError(): bool;
}