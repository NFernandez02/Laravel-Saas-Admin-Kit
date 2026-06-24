<?php

namespace App\Models;

use App\Events\AdminDashboardDataChanged;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property Collection<int, Permission> $permissions
 * @property Collection<int, User> $users
 */
class Role extends Model
{
    protected $fillable = ['name'];

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }

    /**
     * @return BelongsToMany<Permission, $this>
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    protected static function booted() : void
    {
        /*static::created(function (){
            AdminDashboardDataChanged::dispatch();
        });
        static::deleted(function (){
            AdminDashboardDataChanged::dispatch();
        });*/
    }
}
