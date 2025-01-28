<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salutations extends Model
{
    use HasFactory;
    protected $table = 'salutations';

    /**
     * Method used to get user relationship
     * @return HasMany
     */
    public function user():HasMany
    {
        return $this->hasMany(User::class);
    }
}
