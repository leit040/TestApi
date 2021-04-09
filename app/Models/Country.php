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

    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class);

    }
    public function continent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {

        return $this->belongsTo(Continent::class);
    }

}
