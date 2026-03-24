<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    public $timestamps = false;

    public static $roles = [
        1 => 'Супер-админ',
        2 => 'Админ',
        3 => 'Кладовщик'
    ];
    protected $fillable = [
        'name'
    ];

    //Возврат первичного ключа
    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }
}