<?php

namespace Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Src\Auth\IdentityInterface;

class User extends Model implements IdentityInterface
{
    use HasFactory;

    protected $table = "users";

    public $timestamps = false;
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'email',
        'phone',
        'login',
        'password',
        'role_id',
        'avatar'
    ];

    protected static function booted()
    {
        static::created(function ($user) {
            $user->password = md5($user->password);
            $user->save();
        });
    }

    //Выборка пользователя по первичному ключу
    public function findIdentity(int $id)
    {
        return self::where('id', $id)->first();
    }

    //Возврат первичного ключа
    public function getId(): int
    {
        return $this->id;
    }

    //Возврат аутентифицированного пользователя
    public function attemptIdentity(array $credentials)
    {
        return self::where(['login' => $credentials['login'],
            'password' => md5($credentials['password'])])->first();
    }

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function division(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\Models\Division::class, 'user_id');
    }

    public static function searchNameRoleAttribute($value, $role_id)
    {
        return User::where('role_id', $role_id)
            ->whereRaw('LOWER(CONCAT_WS(" ", surname, name, patronymic)) LIKE ?', ["%".mb_strtolower($value)."%"])
            ->orderBy('surname')
            ->orderBy('name')
            ->orderBy('patronymic')
            ->get();
    }

    public static function isUserAdmin(User $user): bool
    {
        return $user->role->id === 1 || $user->role->id === 2;
    }

    public function isAdmin(): bool
    {
        return $this->role->id === 1 || $this->role->id === 2;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role->id === 1;
    }

    public function isStoreKeeper(): bool
    {
        return $this->role->id === 3;
    }
}