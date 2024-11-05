<?php

namespace App\Models;

use Clickbar\Magellan\Database\Eloquent\HasPostgisColumns;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DamageRequest extends Model
{
    use HasUlids;
    use HasPostgisColumns;

    protected $table = 'damage_requests';

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
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function camera(): BelongsTo
    {
        return $this->belongsTo(Camera::class, 'camera_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'damage_request_category');
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        if ($this->user_id == null) {
            return 'cameras';
        } else if ($this->camera_id == null) {
            return 'users';
        } else {
            return 'undefined';
        }
    }
}
