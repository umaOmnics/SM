<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permissions extends Model
{
    use HasFactory;
    protected $table = 'permissions';

    /**
     * Method used to get roles relationship
     * @return BelongsToMany
     */

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Roles::class,'roles_permissions');
    }

    /**
     * Method used to get users relationship along with data.
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'users_permissions');
    }
}
