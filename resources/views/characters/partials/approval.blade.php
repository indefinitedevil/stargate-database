@can('approve', $character)
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <div class="mt-1">
            <h3 class="text-lg font-semibold">{{ __('Ready for approval') }}</h3>

            <form method="POST" action="{{ route('characters.approve', $character) }}" id="character_approval">
                @csrf
                <div>
                    <x-input-label for="notes" :value="__('Notes')"/>
                    <textarea id="notes" name="notes"
                              class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full"></textarea>
                </div>
                <div class="mt-2">
                    <x-primary-button>
                        {{ __('Approve') }}
                    </x-primary-button>
                    <x-secondary-button
                        onclick="let form = document.getElementById('character_approval'); form.action = '{{ route('characters.deny', $character) }}'; form.submit();"
                    >
                        {{ __('Deny') }}
                    </x-secondary-button>
                </div>
            </form>
        </div>
    </div>
@endcan