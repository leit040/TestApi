<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Continent extends Model
{
    use HasFactory;
    protected $fillable = [
       'continent_code'
    ];

public function users(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
{
    return $this->hasManyThrough(User::class,Country::class);

}

public function countries(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(Country::class);
}

}


