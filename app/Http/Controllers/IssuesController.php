<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIssueRequest;
use App\Models\Issues;
use App\Models\Project;
use Illuminate\Http\Request;

class IssuesController extends Controller
{
    public function index(Request $request)
    {
        $query = Issues::query()
        ->with(['project'])
        ->when($request->status, fn($q,$v)=>$q->where('status',$v))
        ->when($request->priority, fn($q,$v)=>$q->where('priority',$v))
        ->latest();

        $issues = $query->paginate(15)->withQueryString();
        return view('issues.index', compact('issues'));
    }

    public function create() {
        $projects = Project::all(); // Get all projects
        return view('issues.create', compact('projects'));
    }

    public function store(StoreIssueRequest $request) {
        $issue = Issues::create($request->validated());
        return redirect()->route('issues.index', $issue)->with('success','Issue created');
    }

    public function show(Issues $issue) {
        $issue->load(['project']);
        return view('issues.show', compact('issue'));
    }

    public function edit(Issues $issue) {
        $projects = Project::all();
        return view('issues.edit', compact('issue','projects'));
    }

    public function update(StoreIssueRequest $request, Issues $issue) {
        $issue->update($request->validated());
        return redirect()->route('issues.show',$issue)->with('success','Issue updated');
    }

    public function destroy(Issues $issue) {
        $issue->delete();
        return redirect()->route('issues.index')->with('success','Issue deleted successfully.');
    }

}
