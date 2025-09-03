@extends('layouts.app')

@section('title','Create Tag')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-gray-800 text-gray-100 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6 text-white">Create Tag</h1>

    <form action="{{ route('tags.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Name Field -->
        <div>
            <label class="block mb-2 font-medium text-gray-300">Name</label>
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                class="w-full p-3 rounded bg-gray-700 border border-gray-600 text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Tag Name"
            >
            @error('name')
                <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <!-- Color Field -->
        <div>
            <label class="block mb-2 font-medium text-gray-300">Color</label>
            <input
                type="text"
                name="color"
                value="{{ old('color') }}"
                class="w-full p-3 rounded bg-gray-700 border border-gray-600 text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="e.g. #ff0000 or red"
            >
            @error('color')
                <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('tags.index') }}" class="p-2 bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded shadow" style="margin-right: 5px">
                Cancel
            </a>
            <button
                type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded shadow"
            >
                Save
            </button>
        </div>
    </form>
</div>
@endsection
