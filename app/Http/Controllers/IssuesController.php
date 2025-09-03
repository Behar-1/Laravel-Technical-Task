<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIssueRequest;
use App\Models\Issues;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IssuesController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Issues::with(['project', 'tags']);
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }
        if ($request->status) $query->where('status', $request->status);
        if ($request->priority) $query->where('priority', $request->priority);
        if ($request->tag) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag); // fully qualified
            });
        }
        $issues = $query->paginate(10);
        $tags = Tag::all();

        if ($request->ajax()) {
            return view('issues.index', compact('issues', 'tags'))->render();
        }

        return view('issues.index', compact('issues', 'tags'));
    }

    public function create() {
        $projects = Project::all(); // Get all projects
        return view('issues.create', compact('projects'));
    }

    public function store(StoreIssueRequest $request) {

        $data = $request->validated();
        $data['user_id'] = Auth::id(); // assign logged-in user as creator
        $issue = Issues::create($data);

        return redirect()->route('issues.index', $issue)->with('success', 'Issue created');
    }

    public function show(Issues $issue) {
        $issue->load(['project', 'tags', 'comments']);
        $tags = Tag::all();
        $users = User::all();
        return view('issues.show', compact('issue', 'tags', 'users'));
    }

    public function edit(Issues $issue) {
        $this->authorize('update', $issue);
        $projects = Project::all();
        return view('issues.edit', compact('issue','projects'));
    }

    public function update(StoreIssueRequest $request, Issues $issue) {
        $this->authorize('update', $issue);
        $issue->update($request->validated());
        return redirect()->route('issues.show',$issue)->with('success','Issue updated');
    }

    public function destroy(Issues $issue) {
        $this->authorize('delete', $issue);
        $issue->delete();
        return redirect()->route('issues.index')->with('success','Issue deleted successfully.');
    }

    public function attachTag(Request $request, $issueId)
    {
        $request->validate(['tag_id' => 'required|exists:tags,id']);
        $issue = Issues::findOrFail($issueId);
        $tag = Tag::findOrFail($request->tag_id);
        if (!$issue->tags->contains($tag->id)) {
            $issue->tags()->attach($tag->id);
        }
        return response()->json(['success' => true, 'tag' => $tag]);
    }

    public function detachTag(Request $request, $issueId)
    {
        $request->validate(['tag_id' => 'required|exists:tags,id']);
        $issue = Issues::findOrFail($issueId);
        $tag = Tag::findOrFail($request->tag_id);
        $issue->tags()->detach($tag->id);
        return response()->json(['success' => true, 'tag' => $tag]);
    }

    public function attachUser(Request $request, $issueId)
    {
        $this->authorize('update', Issues::findOrFail($issueId));
        $request->validate(['user_id' => 'required|exists:users,id']);
        $issue = Issues::findOrFail($issueId);
        $user = User::findOrFail($request->user_id);
        if (!$issue->users->contains($user->id)) {
            $issue->users()->attach($user->id);
        }
        return response()->json(['success' => true, 'user' => $user]);
    }

    public function detachUser(Request $request, $issueId)
    {
        $this->authorize('update', Issues::findOrFail($issueId));
        $request->validate(['user_id' => 'required|exists:users,id']);
        $issue = Issues::findOrFail($issueId);
        $user = User::findOrFail($request->user_id);
        $issue->users()->detach($user->id);
        return response()->json(['success' => true, 'user' => $user]);
    }

}
