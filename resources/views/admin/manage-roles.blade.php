@php
    use App\Models\User;
    use Spatie\Permission\Models\Role;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Manage roles') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage roles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials.errors')
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                @php
                    $roles = Role::all();
                    $cellClass = 'border-b border-slate-100 dark:border-slate-700 p-2 text-center';
                @endphp
                <form method="POST" action="{{ route('admin.store-roles') }}">
                    @csrf
                <table class="table-fixed w-full">
                    <thead>
                    <tr>
                        <th class="{{ $cellClass }}">{{ __('User') }}</th>
                        @foreach($roles as $role)
                            <th class="{{ $cellClass }}">{{ ucfirst($role->name) }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach (User::all() as $user)
                        <tr>
                            <td class="{{ $cellClass }}">{{ $user->name }}</td>
                            @foreach($roles as $role)
                                <td class="{{ $cellClass }}">
                                    <input type="checkbox" name="role[{{ $user->id }}][]"
                                           value="{{ $role->name }}" {{ $user->roles->contains($role) ? 'checked' : '' }}>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                    <div class="flex justify-end">
                    <x-primary-button class="mt-4">
                        {{ __('Save') }}
                    </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
