<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">

        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-gray-800 shadow-md flex-shrink-0">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">My App</h1>
                <nav class="space-y-2">
                    <a href="{{ route('projects.index') }}" class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200">Projects</a>
                    <a href="#" class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200">Issues</a>
                    <a href="#" class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200">X</a>
                    <a href="#" class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200">X</a>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            @include('layouts.navigation')

            <!-- Page Header -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            @if(session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
                </div>
            @endif

            <!-- Page Content -->
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
