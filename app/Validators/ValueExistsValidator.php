<?php


namespace Validators;

use Debug\DebugTools;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Log;
use Src\Validator\AbstractValidator;

class ValueExistsValidator extends AbstractValidator
{
    protected string $message = "The :field should exists.";

    public function rule(): bool
    {
        return (bool)Capsule::table($this->args[0])
            ->where($this->args[1], $this->value)->count();
    }
}