<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static create(array $array)
 * @method static findOrFail(string $id)
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use HasUlids;

    protected $table = 'users';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function damageRequests(): HasMany
    {
        return $this->hasMany(DamageRequest::class, 'user_id', 'id');
    }
}
