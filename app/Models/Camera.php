<?php

namespace App\Models;

use Clickbar\Magellan\Database\Eloquent\HasPostgisColumns;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Camera extends Model
{
    use HasUlids;
    use HasPostgisColumns;

    protected $table = 'cameras';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected array $postgisColumns = [
        'point' => [
            'type' => 'geometry',
            'srid' => 4326,
        ],
    ];

    /**
     * @return HasMany
     */
    public function histories(): HasMany
    {
        return $this->hasMany(CameraHistory::class, 'camera_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function damageRequests(): HasMany
    {
        return $this->hasMany(DamageRequest::class, 'camera_id', 'id');
    }
}
