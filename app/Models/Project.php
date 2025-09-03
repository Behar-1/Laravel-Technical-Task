<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = ['name', 'description'];

    public function issues(): HasMany {
        return $this->hasMany(Issues::class);
    }
}
