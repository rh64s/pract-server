<?php

namespace Validators;

use Debug\DebugTools;
use Src\Validator\AbstractValidator;

class MimeTypesValidator extends AbstractValidator
{
    protected string $message = 'Field :field must be :value type';

    public function rule(): bool
    {
        if (is_array($this->value) && isset($this->value['type'])) {
            $type = $this->value['type'];
            foreach ($this->args as $arg) {
                if (str_starts_with($arg, 'image')) {
                    return true;
                }
                if (str_starts_with($type, $arg)) {
                    return true;
                }
            }
        }
        return false;
    }
}
