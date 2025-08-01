@php
    use App\Models\Department;
    use App\Models\Division;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Organisation Chart') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Organisation Chart') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <div>
            {{ __('Coming soon...') }}
        </div>
    </div>
</x-app-layout>
