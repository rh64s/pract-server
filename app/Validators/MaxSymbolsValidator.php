<?php

namespace Validators;

use Src\Validator\AbstractValidator;

class MaxSymbolsValidator extends AbstractValidator
{

    protected string $message = 'Field :field is long!';

    public function rule(): bool
    {

        return (bool)strlen($this->value) <= (int) $this->args[0];
    }
}
