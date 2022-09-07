<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Shanmuga\LaravelEntrust\Traits\LaravelEntrustUserTrait;
use App\Helpers\Format;

/**
 *
 * Class User
 *
 */
class User extends Authenticatable
{
    use \Laravel\Cashier\Billable;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use LaravelEntrustUserTrait;

    public $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role_id',
        'status_user_id',
        'phone',
        'whatsapp',
        'cpf',
        'hash_id',
        'senha_hash'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function format()
    {
        return (object) [
            "id" => $this->id,
            "hash_id" => $this->hash_id,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "email" => $this->email,
            "email_verified_at" => $this->email_verified_at,
            "status_user_id" => $this->status_user_id,
            "role_id" => $this->role_id,
            "phone" => Format::phone($this->phone),
            "whatsapp" => Format::phone($this->whatsapp),
            "cpf" => Format::cpf($this->cpf),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "senha_hash" => $this->senha_hash,
            "password" => $this->password,
            "roles" => $this->roles()
                ->get()
                ->map->formatWithoutPermissions(),
        ];
    }
}
