<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Roles extends Model
{
    use HasFactory;
    protected $table = 'roles';

    /**
     * Method used to get permissions relationship
     * @return BelongsToMany
     */
    public function permissions():belongsToMany
    {
        return $this->belongsToMany(Permissions::class,'roles_permissions');
    }

    /**
     * Method used to get users relationship
     * @return BelongsToMany
     */
    public function users():belongsToMany
    {
        return $this->belongsToMany(User::class,'users_roles','roles_id','users_id');
    }

    /**
     * Method used to get resources relationship
     * @return BelongsToMany
     */

    public function resources():belongsToMany
    {
        return $this->belongsToMany(Resources::class,'roles_resources','roles_id','resources_id')
                ->withPivot('permissions_id');
    }
}
