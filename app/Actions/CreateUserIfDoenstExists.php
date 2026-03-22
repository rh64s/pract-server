<?php

namespace App\Actions;

use Models\User;

class CreateUserIfDoenstExists
{
    public static function execute()
    {
        if (User::all()->count() === 0) {
            User::create([
                'name' => 'Админ',
                'surname' => 'Админович',
                'login' => 'admin',
                'password' => 'admin',
                'role_id' => 1,
                'email' => 'admin@example.com',
                'phone' => '88005553535',
            ]);
        }
    }
}