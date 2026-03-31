<?php

namespace Controllers\Api;

use Models\UnitType;
use Src\Request;
use BasicValidators\Validator\Validator;
use Src\View;

class UnitTypeController
{
    public function index(): void
    {
        $unit_types = UnitType::orderBy('name')->get();
        (new View())->toJSON($unit_types->toArray());
    }

    public function store(Request $request): void
    {
        $validator = new Validator($request->all(), [
            'name' => ['required', 'max:255', 'min:1', 'unique:unit_types,name'],
        ]);

        if ($validator->fails()) {
            (new View())->toJSON(['errors' => $validator->errors()], 422);
            return;
        }

        $unit_type = UnitType::create($request->all());

        if ($unit_type) {
            (new View())->toJSON($unit_type->toArray(), 201);
        } else {
            (new View())->toJSON(['error' => 'Failed to create unit type'], 500);
        }
    }
}
