<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <x-slot name="title">{{ __('Dashboard') }}</x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
            @include('partials.help')
            <div>
                <h3 class="text-lg font-semibold mt-2">{{ __('Support the coder') }}</h3>
                <p>{!! __('If you find this database helpful, consider <a href="https://ko-fi.com/moonemprah" class="underline">tipping the coder</a>.') !!}</p>
            </div>
        </div>
    </div>
</x-app-layout>
