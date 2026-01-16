<x-app-layout>
    <x-slot name="title">{{ __('Skills') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Skills') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300 space-y-6">
        <p>
            {{ __('This is a list of all skills available within the character database.') }}
            {{ __('This includes a list of feats and abilities gained from those skills, as well as skills unlocked (or locked) by skill purchases.') }}
        </p>
    </div>

    @include('rules/partials/skills')
</x-app-layout>
