@extends('layouts.app')

@section('title','Issues')

@section('content')
<div class="max-w-6xl mx-auto p-8 bg-gray-900 text-gray-100 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-12" style="margin-bottom: 50px">
        <h1 class="font-bold text-white text-2xl" style="font-size: 25px">Issues</h1>
        <a href="{{ route('issues.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded shadow-lg font-semibold">
            Create New Issue
        </a>
    </div>

    <form method="GET" class="mt-4 mb-4 flex gap-2">
        <input type="text" name="search" id="search" placeholder="Search issues..."
           class="border p-2 flex-1" value="{{ request('search') }}">
        <select name="status" class="border p-2">
            <option value="">-- Status --</option>
            <option value="open" {{ request('status')=='open'?'selected':'' }}>Open</option>
            <option value="in_progress" {{ request('status')=='in_progress'?'selected':'' }}>In Progress</option>
            <option value="closed" {{ request('status')=='closed'?'selected':'' }}>Closed</option>
        </select>
        <select name="priority" class="border p-2">
            <option value="">-- Priority --</option>
            <option value="low" {{ request('priority')=='low'?'selected':'' }}>Low</option>
            <option value="medium" {{ request('priority')=='medium'?'selected':'' }}>Medium</option>
            <option value="high" {{ request('priority')=='high'?'selected':'' }}>High</option>
        </select>
        <select name="tag" class="form-select">
            <option value="">All Tags</option>
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}" @selected(request('tag') == $tag->id)>{{ $tag->name }}</option>
            @endforeach
        </select>
        <button class="bg-gray-600 text-white px-3 py-1 rounded">Filter</button>
    </form>

    @if($issues->count())
    <div class="min-w-3xl max-w-6xl mx-auto p-8 space-y-6">
        @foreach($issues as $issue)
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 hover:bg-gray-700 transition">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold">
                        <span class="text-gray-600">Title: </span> <span class="text-gray-300">{{ $issue->title }}</span>
                    </h2>
                    <p class="mt-1">
                        <span class="text-gray-600">Project: </span> <span class="text-gray-300">{{ $issue->project->name }}</span>
                    </p>
                    <p class="mt-1">
                        <span class="text-gray-600">Status: </span> <span class="text-gray-300">{{ ucfirst($issue->status) }}</span>
                    </p>
                    <p class="mt-1">
                        <span class="text-gray-600">Priority: </span> <span class="text-gray-300">{{ ucfirst($issue->priority) }}</span>
                    </p>
                    <p class="mt-2">
                        <span class="text-gray-600">Due Date: </span> <span class="text-gray-300">{{ $issue->due_date ?? '-' }}</span>
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('issues.show', $issue) }}" class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded shadow text-sm">
                        Show
                    </a>
                    <a href="{{ route('issues.edit', $issue) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow text-sm">
                        Edit
                    </a>
                    <form action="{{ route('issues.destroy', $issue) }}" method="POST" onsubmit="return confirm('Delete this issue?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow text-sm">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

        <div class="mt-6">
            {{ $issues->links('pagination::tailwind') }}
        </div>
    </div>

    @else
        <p class="text-gray-400 text-lg">No issues found. Create your first issue!</p>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search');
        const issuesContainer = document.getElementById('issues-container');
        const form = document.getElementById('filter-form');

        let timeout = null;

        searchInput.addEventListener('keyup', function () {
            clearTimeout(timeout);

            timeout = setTimeout(() => {
                const formData = new FormData(form);
                const params = new URLSearchParams(formData).toString();

                fetch(`{{ route('issues.index') }}?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    issuesContainer.innerHTML = html;
                });
            }, 500); // 500ms debounce
        });
    });
    </script>

@endsection
