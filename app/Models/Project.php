<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function projectable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {

        return $this->BelongsTo(User::class);
    }

    public function users(): MorphToMany
    {

        return $this->MorphToMany(User::class,'projectable');
    }

public function labels(): MorphToMany
{
        return $this->MorphToMany(Label::class,'projectable');
}


}
