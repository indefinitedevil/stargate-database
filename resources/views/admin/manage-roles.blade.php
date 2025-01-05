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
                @endphp
                <table class="table-auto">
                    <thead>
                    <tr>
                        <th>{{ __('User') }}</th>
                        @foreach($roles as $role)
                            <th>{{ $role->name }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach (User::all() as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            @foreach($roles as $role)
                                <td>
                                    <input type="checkbox" name="role[{{ $user->id }}][]"
                                           value="{{ $role->id }}" {{ $user->roles->contains($role) ? 'checked' : '' }}>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
