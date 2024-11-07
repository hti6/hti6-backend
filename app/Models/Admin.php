<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Model;

class Admin extends Model
{
    use HasFactory;
    use HasUlids;

    protected $table = 'admins';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];
}
