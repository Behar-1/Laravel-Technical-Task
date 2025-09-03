@extends('layouts.app')

@section('title','Edit Issue')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-gray-800 text-gray-100 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6 text-white">Edit Issue</h1>

    <form action="{{ route('issues.update', $issue) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Project Field -->
        <div>
            <label class="block mb-2 font-medium text-gray-300">Project</label>
            <select
                name="project_id"
                class="w-full p-3 rounded bg-gray-700 border border-gray-600 text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ old('project_id', $issue->project_id) == $project->id ? 'selected' : '' }}>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
            @error('project_id')
                <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <!-- Title Field -->
        <div>
            <label class="block mb-2 font-medium text-gray-300">Title</label>
            <input
                type="text"
                name="title"
                value="{{ old('title', $issue->title) }}"
                class="w-full p-3 rounded bg-gray-700 border border-gray-600 text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Issue Title"
            >
            @error('title')
                <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <!-- Description Field -->
        <div>
            <label class="block mb-2 font-medium text-gray-300">Description</label>
            <textarea
                name="description"
                rows="5"
                class="w-full p-3 rounded bg-gray-700 border border-gray-600 text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Issue Description"
            >{{ old('description', $issue->description) }}</textarea>
            @error('description')
                <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <!-- Status Field -->
        <div>
            <label class="block mb-2 font-medium text-gray-300">Status</label>
            <select
                name="status"
                class="w-full p-3 rounded bg-gray-700 border border-gray-600 text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="open" {{ old('status', $issue->status) == 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ old('status', $issue->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="closed" {{ old('status', $issue->status) == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
            @error('status')
                <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <!-- Priority Field -->
        <div>
            <label class="block mb-2 font-medium text-gray-300">Priority</label>
            <select
                name="priority"
                class="w-full p-3 rounded bg-gray-700 border border-gray-600 text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="low" {{ old('priority', $issue->priority) == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ old('priority', $issue->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ old('priority', $issue->priority) == 'high' ? 'selected' : '' }}>High</option>
            </select>
            @error('priority')
                <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <!-- Due Date Field -->
        <div>
            <label class="block mb-2 font-medium text-gray-300">Due Date</label>
            <input
                type="date"
                name="due_date"
                value="{{ old('due_date', $issue->due_date) }}"
                class="w-full p-3 rounded bg-gray-700 border border-gray-600 text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            @error('due_date')
                <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('issues.index') }}" class="p-2 bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded shadow" style="margin-right: 5px">
                Cancel
            </a>
            <button
                type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded shadow"
            >
                Update
            </button>
        </div>
    </form>
</div>
@endsection
