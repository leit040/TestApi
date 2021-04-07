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
        'user_id'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function linkedUsers(): BelongsToMany
    {

        return $this->belongsToMany(User::class);
    }

public function labels(): BelongsToMany
{
        return $this->belongsToMany(Label::class);
}


}
