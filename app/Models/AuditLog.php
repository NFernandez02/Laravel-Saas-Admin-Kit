<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
/**
 * @property int $id
 * @property int $user_id
 * @property User|null $user
 * @property string $action
 * @property string $target_type
 * @property int $target_id
 * @property string $description
 * 
 */
class AuditLog extends Model
{
    protected $fillable = ['user_id', 'action', 'target_type', 'target_id', 'description'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
