<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Issues extends Model
{
    protected $fillable = ['project_id', 'title', 'description', 'status', 'priority', 'due_date'];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class,
        'issue_tag',
        'issue_id',
        'tag_id'
        )->withTimestamps();
    }
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'issue_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'issue_user', 'issue_id', 'user_id');
    }
}
