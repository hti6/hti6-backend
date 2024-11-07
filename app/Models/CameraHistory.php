<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CameraHistory extends Model
{
    use HasUlids;

    protected $table = 'camera_history';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function camera(): BelongsTo
    {
        return $this->belongsTo(Camera::class, 'camera_id', 'id');
    }
}
