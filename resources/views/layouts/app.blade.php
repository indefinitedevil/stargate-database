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
    <script src="https://kit.fontawesome.com/39122919d6.js" crossorigin="anonymous"></script>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    @include('layouts.navigation')

    @if ('local' == config('app.env'))
        <div class="bg-orange-100 text-orange-700 sm:grid sm:grid-cols-6">
            <div class="sm:col-span-4 sm:col-start-2 py-2 px-4 sm:px-6 lg:px-8">
                <p class="font-bold">{{ __('This is a development version of the Stargate Database. Changes made here will not reflect on the live database.') }}</p>
            </div>
        </div>
    @endif

    <!-- Page Heading -->
    @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow sm:grid sm:grid-cols-6">
            <div class="sm:col-span-4 sm:col-start-2 py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Page Content -->
    <main class="sm:grid sm:grid-cols-6">
        <div class="sm:col-span-4 sm:col-start-2 py-6 sm:px-6 lg:px-8 space-y-6">
            @include('partials.downtimes')
            @include('partials.errors')
            {{ $slot }}
        </div>
    </main>

    <footer class="pt-6 sm:pt-12 pb-4 sm:grid sm:grid-cols-6">
        <div class="sm:col-span-4 sm:col-start-2 py-6 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="float-right">
                        <a href="https://discord.gg/yjQBgYSA4T" target="_blank"
                           class="px-2 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-2"
                           title="{{ __('Stargate Discord Server') }}"><i class="fa-brands fa-discord"></i></a>
                        <a href="https://www.facebook.com/groups/stargatelrp" target="_blank"
                           class="px-2.5 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-2"
                           title="{{ __('Stargate Facebook Group') }}"><i class="fa-brands fa-facebook"></i></a>
                    </div>
                    <div class="text-sm">
                        <p>
                            {!! sprintf(__('<a href="%s" class="underline" target="_blank">Code</a> developed for <a href="%s" class="underline" target="_blank">Stargate LARP</a> by <a href="%s" class="underline" target="_blank">Eligos</a> and Bobbie.'), 'https://github.com/indefinitedevil/stargate-database', 'https://www.stargatelarp.co.uk/', 'https://ko-fi.com/moonemprah') !!}
                            {!! sprintf(__('SEF logo by <a href="%s" class="underline" target="_blank">Charlie</a> based on a design by Mark.'), 'https://www.etsy.com/uk/shop/CharlieTeesTrove') !!}
                        </p>
                        <p>
                            {{ __('The assistance with testing and copy-pasting from society members is truly appreciated!') }}
                        </p>
                        <p>
                        </p>
                    </div>
                </div>
            </div>
            <div class="py-2 px-6 text-gray-900 dark:text-gray-100 text-center text-sm gap-4 flex justify-center">
                <a href="{{ route('changelog') }}" class="inline-block underline">{{ __('Changelog') }}</a>
                <a href="{{ route('privacy') }}" class="inline-block underline">{{ __('Privacy Policy') }}</a>
                <a href="{{ route('roles') }}" class="inline-block underline">{{ __('Roles') }}</a>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
