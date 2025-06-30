<x-app-layout>
    <x-slot name="title">{{ __('User Roles') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Roles') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
            <p>{{ __('Roles are used to determine what a user can do on the site. Each user can have multiple roles, and each role can have multiple permissions.') }}</p>
            <p>{{ __('This is a breakdown of what roles are available on the site and what they do.') }}</p>
            @foreach($roles as $role)
                <h3 class="text-lg font-semibold">{{ ucwords($role->name) }}</h3>
                <ul class="list-disc list-inside mt-2">
                    @foreach($role->permissions as $permission)
                        <li>{{ ucfirst($permission->name) }}</li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    </div>
</x-app-layout>
