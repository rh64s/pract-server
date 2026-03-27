<?php

namespace Validators;

use Src\Validator\AbstractValidator;

class PhoneValidator extends AbstractValidator
{

    protected string $message = 'Field :field is not phone number.';

    public function rule(): bool
    {

        return (bool)preg_match('/^.[0-9]*$/', $this->value);
    }
}
