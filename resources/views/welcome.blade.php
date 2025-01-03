<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Welcome') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                    <p>
                        This is a character database system for the
                        <a class=underline" target="_blank" href="https://www.stargatelarp.co.uk/">Stargate LARP</a>.
                        Within you will be able to create and manage your characters.
                        Eventually a downtime system will be added.
                    </p>
                    @guest
                        <p>
                            You'll need to
                            <a class="underline" href="{{ route('login') }}">log in</a>
                            @if (Route::has('register'))
                                or  <a class="underline" href="{{ route('register') }}">register</a>
                            @endif
                            to access the character creator.
                        </p>
                    @endguest
                    @auth
                        @include('partials.help')
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
