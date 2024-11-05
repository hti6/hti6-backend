<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasUlids;

    protected $table = 'notifications';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];
}
