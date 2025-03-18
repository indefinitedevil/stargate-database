@php
    use App\Models\Event;
    use App\Models\User;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Attendance: :event', ['event' => $event->name]) }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Attendance: :event', ['event' => $event->name]) }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        @php
            $roles = [Event::ROLE_PLAYER, Event::ROLE_RUNNER, Event::ROLE_CREW];
            $cellClass = 'border-b border-slate-100 dark:border-slate-700 p-2 text-center';
            $eventRoles = $attended = $characters = [];
            foreach ($event->users as $user) {
                $eventRoles[$user->id] = $user->pivot->role;
                $attended[$user->id] = $user->pivot->attended;
                $characters[$user->id] = $user->pivot->character_id;
            }
        @endphp
        <form method="POST" action="{{ route('events.store-attendance') }}">
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id }}">
            <table class="table-fixed w-full">
                <thead>
                <tr>
                    <th class="{{ $cellClass }}">{{ __('User') }}</th>
                    <th class="{{ $cellClass }}">{{ __('Character') }}</th>
                    <th class="{{ $cellClass }}">{{ __('Not booked') }}</th>
                    @foreach($roles as $role)
                        <th class="{{ $cellClass }}">{{ Event::roleName($role) }}</th>
                    @endforeach
                    <th class="{{ $cellClass }}">{{ __('Attended') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach (User::orderBy('name', 'asc')->get() as $user)
                    <tr>
                        <td class="{{ $cellClass }}">{{ $user->name }}</td>
                        <td class="{{ $cellClass }}">
                            <x-select name="attendance[{{ $user->id }}][character_id]" class="w-full">
                                <option value="">{{ __('None') }}</option>
                                @foreach($user->characters as $character)
                                    <option value="{{ $character->id }}"
                                        {{ !empty($characters[$user->id]) && $characters[$user->id] == $character->id ? 'selected' : '' }}>
                                        {{ $character->name }}
                                    </option>
                                @endforeach
                            </x-select>
                        </td>
                        <td class="{{ $cellClass }}">
                            <input type="radio" name="attendance[{{ $user->id }}][role]" value="0"
                                {{ empty($eventRoles[$user->id]) ? 'checked' : '' }}>
                        </td>
                        @foreach($roles as $role)
                            <td class="{{ $cellClass }}">
                                <input type="radio" name="attendance[{{ $user->id }}][role]"
                                       value="{{ $role }}" {{ !empty($eventRoles[$user->id]) && $eventRoles[$user->id] == $role ? 'checked' : '' }}>
                            </td>
                        @endforeach
                        <td class="{{ $cellClass }}">
                            <input type="checkbox" name="attendance[{{ $user->id }}][attended]"
                                {{ !empty($attended[$user->id]) ? 'checked' : '' }}>
                        </td>
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
</x-app-layout>
