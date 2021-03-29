<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable = [
        'continent_id',
        'name'

    ];

    public function users(){
        return $this->belongsToMany(User::class);

    }
    public function continent(){

        return $this->belongsTo(Continent::class);
    }

}
