<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;
protected $fillable=[
    'name'
];
public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(User::class);
}
    public function projects(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Project::class,'projectable');
    }
}
