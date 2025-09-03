<?php

namespace App\Policies;

use App\Models\Issues;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IssuesPolicy
{
    public function update(User $user, Issues $issue)
    {
        return $user->id === $issue->user_id;
    }

    public function delete(User $user, Issues $issue)
    {
        return $user->id === $issue->user_id;
    }
}
