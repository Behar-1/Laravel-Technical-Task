@extends('layouts.app')

@section('title','Projects')

@section('content')
<div class="max-w-6xl mx-auto p-8 bg-gray-900 text-gray-100 rounded-lg shadow-lg">
    <div class="flex justify-between items-center" style="margin-bottom: 50px">
        <h1 class="font-bold text-white" style="font-size: 25px">Projects</h1>
        <a href="{{ route('projects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded shadow-lg font-semibold">
            Create New Project
        </a>
    </div>

    @if($projects->count())
    <div class="min-w-3xl max-w-6xl mx-auto p-8 space-y-6">
        @foreach($projects as $project)
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 hover:bg-gray-700 transition">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold">
                        <span class="text-gray-600">Name: </span> <span class="text-gray-300">{{ $project->name }}</span>
                    </h2>
                    <p class="mt-2">
                        <span class="text-gray-600">Description: </span> <span class="text-gray-300">{{ $project->description }}</span>
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('projects.show', $project) }}" class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded shadow text-sm">
                        Show
                    </a>
                    <a href="{{ route('projects.edit', $project) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow text-sm">
                        Edit
                    </a>
                    <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Delete this project?')">
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
            {{ $projects->links('pagination::tailwind') }}
        </div>
    </div>

    @else
        <p class="text-gray-400 text-lg">No projects found. Create your first project!</p>
    @endif
</div>
@endsection
