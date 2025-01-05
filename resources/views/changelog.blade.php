<x-app-layout>
    <x-slot name="title">{{ __('Changelog') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Changelog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                    <h3 class="text-lg font-semibold">5th January 2025</h3>
                    <ul class="list-inside list-disc">
                        <li>Initial release</li>
                        <li>Added mobile improvements</li>
                        <li>Added changelog</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
