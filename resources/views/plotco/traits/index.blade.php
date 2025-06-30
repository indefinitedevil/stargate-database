<x-app-layout>
    <x-slot name="title">{{ __('Character Traits') }}</x-slot>
    <x-slot name="header">
        <a href="{{ route('plotco.traits.create') }}"
           class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
        >{{ __('Create') }}</a>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Character Traits') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
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
