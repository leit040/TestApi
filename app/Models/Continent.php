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

public function users(){
    return $this->hasManyThrough(User::class,Country::class);

}

public function countries(){
    return $this->hasMany(Country::class);
}

}


