<?php

namespace Validators;

use Src\Validator\AbstractValidator;

class RawRegexValidation extends AbstractValidator
{
    protected string $message = 'Field :field is not correct.';

    public function rule(): bool
    {
        return (bool)preg_match($this->args[0], $this->value);
    }
}