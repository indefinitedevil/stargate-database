<x-app-layout>
    <x-slot name="title">{{ __('Membership Verification') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Membership Verification') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
        <div>
            <form method="POST" action="{{ route('memberships.verify') }}">
                @csrf
                <x-input-label for="membership_number" :value="__('Enter membership number to verify membership status')" />
                <x-text-input id="membership_number" name="membership_number" type="text" class="mt-1 block w-full sm:w-1/2"
                              :value="old('membership_number', $user->membershipNumber ?? '')" required autofocus />
                <x-primary-button class="mt-2">{{ __('Verify') }}</x-primary-button>
            </form>
            @if ($user)
            <div>
                <h2 class="text-xl font-semibold mt-4">{{ __('Verification Result') }}</h2>
                <div class="grid grid-cols-2 gap-x-4 gap-y-2 sm:w-1/2">
                    <strong>{{ __('Membership Number:') }}</strong>
                    <span>{{ $user->membershipNumber }}</span>

                    <strong>{{ __('Membership Status:') }}</strong>
                    <span>{{ $user->membershipStatus }}</span>

                    <strong>{{ __('Name:') }}</strong>
                    <span>{{ $user->membership_name }}</span>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
