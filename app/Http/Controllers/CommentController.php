<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Issues;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request, Issues $issue): JsonResponse {
        $comments = $issue->comments()->latest()->paginate(10);
        return response()->json($comments);
    }

    public function store(StoreCommentRequest $request, Issues $issue): JsonResponse {
        $comment = $issue->comments()->create($request->validated());
        return response()->json($comment, 201);
    }
}
