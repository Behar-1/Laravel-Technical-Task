@extends('layouts.app')

@section('title','Tags')

@section('content')
<div class="max-w-6xl mx-auto p-8 bg-gray-900 text-gray-100 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-12" style="margin-bottom: 50px">
        <h1 class="font-bold text-white" style="font-size: 25px">Tags</h1>
        <a href="{{ route('tags.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded shadow-lg font-semibold">
            Create New Tag
        </a>
    </div>

    @if($tags->count())
    <div class="min-w-3xl max-w-6xl mx-auto p-8 space-y-6">
        @foreach($tags as $tag)
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 hover:bg-gray-700 transition">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold">
                        <span class="text-gray-600">Name: </span>
                        <span style="color: {{ $tag->color ?? '#ccc' }}">{{ $tag->name }}</span>
                    </h2>
                </div>
                <div class="flex space-x-3">
                    <form action="{{ route('tags.destroy', $tag) }}" method="POST" onsubmit="return confirm('Delete this tag?')">
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
            {{ $tags->links('pagination::tailwind') }}
        </div>
    </div>
    @else
        <p class="text-gray-400 text-lg">No tags found. Create your first tag!</p>
    @endif
</div>
@endsection
