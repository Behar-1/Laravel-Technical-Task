@extends('layouts.app')

@section('title', $project->name)

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-gray-800 text-gray-100 rounded-lg shadow-md text-center">
    <!-- Project Header -->
    <h1 class="text-3xl font-bold mb-4 text-white">{{ $project->name }}</h1>
    <p class="mb-6 text-gray-300">{{ $project->description }}</p>

    <!-- Action Buttons -->
    <div class="flex space-x-4 justify-center">
        <a href="{{ route('projects.index') }}" class="p-2 bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded shadow" style="margin-right: 5px">
            Cancel
        </a>
        <a href="{{ route('projects.edit', $project) }}" class="bg-yellow-500 hover:bg-yellow-600 text-gray-400 font-semibold px-4 py-2 rounded shadow">
            Edit
        </a>

        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button
                type="submit"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded shadow"
                onclick="return confirm('Delete this project?')"
            >
                Delete
            </button>
        </form>
    </div>
</div>
@endsection
