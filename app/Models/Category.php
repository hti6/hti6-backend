<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasUlids;
    use HasFactory;

    protected $table = 'categories';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];
}
