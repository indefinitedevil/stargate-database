<x-app-layout>
    <x-slot name="title">{{ __('Character Traits') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Character Traits') }}
        </h2>
    </x-slot>
    <x-slot name="sidebar2">
        <x-dropdown-link href="{{ route('plotco.traits.create') }}">
            <i class="fa-solid fa-plus min-w-8"></i>
            {{ __('Create') }}
        </x-dropdown-link>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
            <ul class="list-disc list-inside space-y-2">
                @if ($traits->isEmpty())
                    <li>{{ __('No character traits available') }}</li>
                @else
                    @foreach($traits as $trait)
                        <li>
                            <a href="{{ route('plotco.traits.edit', ['traitId' => $trait->id]) }}"
                               class="underline"><i class="fa-solid {{ $trait->icon }}"></i> {{ $trait->name }}</a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>

    <div class="mt-6">
        {{ $traits->links() }}
    </div>
</x-app-layout>
