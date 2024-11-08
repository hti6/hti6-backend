<?php

namespace App\Models;

use App\Services\NotificationService;
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

    protected static function boot()
    {
        parent::boot();

        static::created(function (DamageRequest $damageRequest) {
            $notificationService = new NotificationService();
            foreach (User::all() as $user) {
                try {
                    $notificationService->notify(
                        $user,
                        'Новое повреждение',
                        'У вас появилось новое повреждение по координатам: ' . $damageRequest->point->getX() . ', ' . $damageRequest->point->getY()
                    );
                } catch (\Throwable $exception) {

                }
            }
            foreach (Admin::all() as $admin) {
                try {
                    $notificationService->notify(
                        $admin,
                        'Новое повреждение',
                        'У вас появилось новое повреждение по координатам: ' . $damageRequest->point->getX() . ', ' . $damageRequest->point->getY()
                    );
                } catch (\Throwable $exception) {

                }
            }
        });
    }

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
