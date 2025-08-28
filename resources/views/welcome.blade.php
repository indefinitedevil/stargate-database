<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Welcome') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
            <p>
                This is a character database system for
                <a class="underline" target="_blank" href="https://www.stargatelarp.co.uk/">Stargate LARP</a>.
                Within you will be able to <a class="underline" href="{{ route('characters.index') }}">create and manage
                    your characters</a> and <a class="underline" href="{{ route('downtimes.index') }}">their downtimes</a>.
            </p>
            @guest
                <p>
                    You'll need to
                    <a class="underline" href="{{ route('login') }}">log in</a>
                    @if (Route::has('register'))
                        or  <a class="underline" href="{{ route('register') }}">register</a>
                    @endif
                    to access the various systems.
                </p>
            @endguest
        </div>
    </div>
</x-app-layout>
