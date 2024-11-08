<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Model;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Model
{
    use HasFactory;
    use HasUlids;
    use HasApiTokens;

    protected $table = 'admins';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'userable');
    }
}
