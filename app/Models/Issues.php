<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Issues extends Model
{
    protected $fillable = ['project_id', 'title', 'description', 'status', 'priority', 'due_date'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
