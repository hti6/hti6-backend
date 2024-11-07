<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function damageRequests(): BelongsToMany
    {
        return $this->belongsToMany(DamageRequest::class, 'damage_request_category');
    }
}
