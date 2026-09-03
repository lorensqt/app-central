<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Title extends Model
{
    protected $fillable = [
        'group',
        'title',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
