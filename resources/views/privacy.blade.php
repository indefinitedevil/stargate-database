<x-app-layout>
    <x-slot name="title">>{{ __('Privacy Policy') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Privacy Policy') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                    <p>
                        This is a no-nonsense privacy policy.
                    </p>
                    <p>
                        The only data collected is that which is needed to operate the system, namely:
                    </p>
                    <ul class="list-disc list-inside">
                        <li>Your name</li>
                        <li>Your email address</li>
                        <li>Your character data</li>
                    </ul>
                    <p>
                        This list may expand as the system is developed and new features are added.
                    </p>
                    <p>
                        This data is only used for internal purposes and will never be shared with third parties.
                    </p>
                    <p>
                        The only cookies in use are those relating to logging in and out of the system.
                        There are no tracking cookies or any other form of tracking in use.
                    </p>
                    <p>
                        Data may pass through servers outside the EU as a result of APIs being used to send emails.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
