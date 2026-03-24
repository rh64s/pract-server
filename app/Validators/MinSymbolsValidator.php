<?php

namespace Validators;

use Src\Validator\AbstractValidator;

class MinSymbolsValidator extends AbstractValidator
{

    protected string $message = 'Field :field should not be more than :max characters';

    public function rule(): bool
    {

        return (bool)strlen($this->value) <= (int) $this->args[0];
    }
}