@php
    use App\Models\Background;
    use App\Models\Status;
    $fieldClass = 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full';
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Create character') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create character') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="mt-1">
                    <form method="POST" action="{{ route('characters.store') }}">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="status_id" value="{{ Status::NEW }}">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="name">Name</label>
                                <input id="name" class="{{ $fieldClass }}" type="text" name="name" required autofocus/>
                            </div>

                            <div>
                                <label for="former_rank">Former Rank (if applicable)</label>
                                <input id="former_rank" class="{{ $fieldClass }}" type="text" name="former_rank"/>
                            </div>

                            <div>
                                <label for="background">Background</label>
                                <select id="background" name="background_id" class="{{ $fieldClass }}" required>
                                    @foreach(Background::all() as $background)
                                        <option value="{{ $background->id }}">{{ $background->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="history">History</label>
                                <textarea id="history" class="{{ $fieldClass }}" name="history"></textarea>
                            </div>

                            <div class="flex items-center gap-4">
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Create
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
