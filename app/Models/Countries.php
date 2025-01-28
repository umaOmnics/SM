<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Countries extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id', 'name'
    ];

    public function companies()
    {
        return $this->hasMany(Partners::class, 'country_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'countries_id');
    }

    public function regulationsRegisters()
    {
        return $this->hasMany(RegulationsRegisters::class, 'countries_id');
    }

    public function regulationsLegalFields()
    {
        return $this->hasMany(Countries::class, 'country_id');
    }

    public function regulationsAuthorities()
    {
        return $this->hasMany(Countries::class, 'country_id');
    }

    public function regulationsLegalNatures()
    {
        return $this->hasMany(Countries::class, 'country_id');
    }

    public function regulationsLevels()
    {
        return $this->hasMany(Countries::class, 'country_id');
    }

    public function regulationsSpecifications()
    {
        return $this->hasMany(Countries::class, 'country_id');
    }

    public function events()
    {
        return $this->hasMany(Events::class, 'countries_id');
    }

    public function partners()
    {
        return $this->hasMany(Partners::class, 'countries_id');
    }

    public function checksTypes()
    {
        return $this->hasMany(ChecksTypes::class, 'countries_id');
    }

} // End class
