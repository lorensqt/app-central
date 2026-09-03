<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Committee extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Get the titles defined under this committee.
     */
    public function titles(): HasMany
    {
        return $this->hasMany(Title::class, 'group', 'name');
    }
}
