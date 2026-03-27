<?php

namespace Validators;

use Src\Validator\AbstractValidator;

class EmailValidator extends AbstractValidator
{

    protected string $message = 'Field :email is not email.';

    public function rule(): bool
    {

        return (bool)preg_match('/^.[0-9a-zA-Z.-]*@.[a-zA-Z]*\..[a-zA-Z]*$/', $this->value);
    }
}
