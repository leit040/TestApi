<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
    public function users(){

        return $this->belongsToMany(User::class);
    }

public function labels(){
        return $this->belongsToMany(Label::class);
}


}
