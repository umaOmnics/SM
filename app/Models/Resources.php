<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Resources extends Model
{
    use HasFactory;
    protected $table = 'resources';

    /**
     * Method used to get the users relationship
     * @return BelongsToMany
     */
    public function users():BelongsToMany
    {
        return $this->belongsToMany(User::class,'users_resources_permissions');
    }

    /**
     * Method used to get the roles relationship
     * @return BelongsToMany
     */
    public function roles():BelongsToMany
    {
        return $this->belongsToMany(Roles::class,'roles_resources','roles_id','resources_id')
                ->withPivot('permissions_id');
    }
}
