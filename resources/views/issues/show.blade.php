@extends('layouts.app')

@section('title', $issue->title)

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-gray-800 text-gray-100 rounded-lg shadow-md text-center">
    <!-- Issue Header -->
    <h1 class="text-3xl font-bold mb-4 text-white">{{ $issue->title }}</h1>

    <!-- Issue Details -->
    <p class="mb-2 text-gray-300">Project: {{ $issue->project->name }}</p>
    <p class="mb-2 text-gray-300">Status: {{ ucfirst(str_replace('_',' ',$issue->status)) }}</p>
    <p class="mb-2 text-gray-300">Priority: {{ ucfirst($issue->priority) }}</p>
    <p class="mb-4 text-gray-300">{{ $issue->description }}</p>
    <p class="mb-6 text-gray-300">Due Date: {{ $issue->due_date ?? '-' }}</p>

    <!-- Action Buttons -->
    <div class="flex space-x-4 justify-center">
        <a href="{{ route('issues.index') }}" class="p-2 bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded shadow">
            Cancel
        </a>
        <a href="{{ route('issues.edit', $issue) }}" class="bg-yellow-500 hover:bg-yellow-600 text-gray-400 font-semibold px-4 py-2 rounded shadow">
            Edit
        </a>

        <form action="{{ route('issues.destroy', $issue) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button
                type="submit"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded shadow"
                onclick="return confirm('Delete this issue?')"
            >
                Delete
            </button>
        </form>
    </div>
</div>
@endsection
