<?php
namespace Controllers\Api;

use Models\User;
use Src\Auth\Auth;
use Src\Request;
use Src\View;
use BasicValidators\Validator\Validator;

class UserController
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $role_id = isset($request->all()['role']) ? $request->all()['role'] : 3;
        $search = isset($request->all()['search']) ? $request->all()['search'] : null;

        $can_view = $user->isSuperAdmin() ? [2, 3] : [3];

        if (!in_array($role_id, $can_view)) {
            (new View())->toJSON(['error' => 'Unauthorized'], 403);
            return;
        }

        $query = User::where('role_id', $role_id);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('patronymic', 'like', "%{$search}%");
            });
        }

        $users = $query->get()->toArray();

        (new View())->toJSON($users);
    }

    public function store(Request $request): void
    {
        $user = Auth::user();
        $can_create = $user->isSuperAdmin() ? [2, 3] : [3];

        $validator = new Validator($request->all(), [
            'name' => ['required', 'max:255', 'min:3'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email', 'min:5'],
            'surname' => ['required', 'max:255', 'min:3'],
            'phone' => ['required', 'max:13', 'min:3', 'unique:users,phone', 'phone'],
            'patronymic' => ['max:255'],
            'role_id' => ['required', 'exists:roles,id'],
            'login' => ['required', 'unique:users,login', 'max:255', 'min:3', 'regex:/^[a-zA-Z0-9]*$/'],
            'password' => ['required', 'min:5', 'max:255']
        ]);

        if ($validator->fails()) {
            (new View())->toJSON(['errors' => $validator->errors()], 422);
            return;
        }

        if (!in_array($request->all()['role_id'], $can_create)) {
            (new View())->toJSON(['error' => 'Unauthorized'], 403);
            return;
        }

        $user = User::create($request->all());

        if ($user) {
            (new View())->toJSON($user->toArray(), 201);
        } else {
            (new View())->toJSON(['error' => 'Failed to create user'], 500);
        }
    }
}
