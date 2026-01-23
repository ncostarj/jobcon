<?php

namespace App\Models;

use App\Domain\Jobs\Models\Ponto;
use App\Domain\Jobs\Models\Role;
use App\Domain\Shared\Traits\Uuid;
use Laravel\Sanctum\HasApiTokens;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Uuid, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
		'name',
		'email_comercial',
		'email',
		'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

	public function roles()
	{
		return $this->belongsToMany(Role::class)->orderBy('nome', 'asc');
	}

	public function pontos()
	{
		return $this->hasMany(Ponto::class, 'user_id');
	}

	public function getUserRoleNamesAttribute($value)
	{
		return $this->roles->implode('nome', ', ');
	}

	public function getRolesActionsAttribute($value)
	{
		return $this->roles->flatMap->actions->unique('id')->values();
	}
}
