@php
    use App\Models\Event;
    use App\Models\User;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Record attendance: :event', ['event' => $event->name]) }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Record attendance: :event', ['event' => $event->name]) }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
        @php
            $roles = [Event::ROLE_PLAYER, Event::ROLE_RUNNER, Event::ROLE_CREW, Event::ROLE_PAID_DOWNTIME];
            $cellClass = 'border-b border-slate-100 dark:border-slate-700 p-2';
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
            <table class="table-auto w-full">
                <thead>
                <tr>
                    <th class="{{ $cellClass }} text-left"><span class="hidden sm:inline">{{ __('Attended') }}</span></th>
                    <th class="{{ $cellClass }} text-left">{{ __('User') }}</th>
                    <th class="{{ $cellClass }} text-left">{{ __('Role/Character') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach (User::orderBy('name', 'asc')->get() as $user)
                    <tr>
                        <td class="{{ $cellClass }}">
                            <label>
                                <input type="checkbox" name="attendance[{{ $user->id }}][attended]"
                                    {{ !empty($attended[$user->id]) ? 'checked' : '' }}>
                                <span class="hidden sm:inline">{{ __('Attended') }}</span>
                            </label>
                        </td>
                        <td class="{{ $cellClass }}">{{ $user->name }}</td>
                        <td class="{{ $cellClass }}">
                            <x-select name="attendance[{{ $user->id }}][role]" class="w-full sm:w-1/3">
                                <option value="0" {{ empty($eventRoles[$user->id]) ? 'selected' : '' }}>
                                    {{ __('None') }}
                                </option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}"
                                        {{ !empty($eventRoles[$user->id]) && $eventRoles[$user->id] == $role ? 'selected' : '' }}>
                                        {{ Event::roleName($role) }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-select name="attendance[{{ $user->id }}][character_id]" class="w-full sm:w-1/2">
                                <option value="">{{ __('None') }}</option>
                                @foreach($user->characters as $character)
                                    <option value="{{ $character->id }}"
                                        {{ !empty($characters[$user->id]) && $characters[$user->id] == $character->id ? 'selected' : '' }}>
                                        {{ $character->listName }}
                                    </option>
                                @endforeach
                            </x-select>
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
