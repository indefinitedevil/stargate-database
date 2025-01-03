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
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="float-right">
                        <a href="https://discord.gg/yjQBgYSA4T" target="_blank"
                           class="px-2 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-2"
                           title="{{ __('Stargate Discord Server') }}"><i class="fa-brands fa-discord"></i></a>
                        <a href="https://www.facebook.com/groups/stargatelrp" target="_blank"
                           class="px-2 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-2"
                           title="{{ __('Stargate Facebook Group') }}"><i class="fa-brands fa-facebook"></i></a>
                    </div>
                    <div class="space-y-2 text-sm">
                        <p>
                            {!! sprintf(__('<a href="%s" class="underline" target="_blank">Code</a> developed for <a href="%s" class="underline" target="_blank">Stargate LARP</a> by Eligos and Bobbie.'), 'https://github.com/indefinitedevil/stargate-database', 'https://www.stargatelarp.co.uk/') !!}
                            {{ __('The assistance with testing and copy-pasting from society members was truly appreciated!') }}
                        </p>
                        <p>
                            {!! sprintf(__('Stargate logo by Jamieson Wieser from <a href="%s" class="underline" target="_blank">Noun Project</a> (CC BY 3.0)'), 'https://thenounproject.com/icon/stargate-1638250/') !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
