<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@isset($title)
            {{ $title }} -
        @endif{{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    @include('layouts.navigation')

    <!-- Page Heading -->
    @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>

    <footer class="pt-12 pb-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2 text-sm">
                    <p>
                        <a href="https://github.com/indefinitedevil/stargate-database" target="_blank" class="underline">Code</a> developed for <a class="underline" href="https://www.stargatelarp.co.uk/" target="_blank">Stargate LARP</a> by Eligos and Bobbie. Assistance with testing and copy-pasting from society members truly appreciated!
                    </p>
                    <p>
                        Stargate logo by Jamison Wieser from <a class="underline" href="https://thenounproject.com/icon/stargate-1638250/" target="_blank" title="Stargate Icons">Noun Project</a> (CC BY 3.0)
                    </p>
                </div>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
