<?php

namespace Src\Auth;

use Models\User;
use Illuminate\Database\Capsule\Manager;
class TokenWorker
{
    public static function generateToken(User $user)
    {
        $token = bin2hex(random_bytes(32));
        Manager::table("sessions")->insert([
            'token' => $token,
            'user_id' => $user->id
        ]);
        return $token;
    }

    public static function user(string $token)
    {
        return User::find(Manager::table("sessions")->where('token', $token)->user_id->first());
    }

    public static function clearToken(string $token)
    {
        Manager::table("sessions")->where('token', $token)->delete();
    }

    public static function clearAllUserTokens(User $user)
    {
        Manager::table("sessions")->where('user_id', $user->id)->delete();
    }
}