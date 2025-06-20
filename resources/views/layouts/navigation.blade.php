@php
    use App\Models\Character;
    use App\Models\Skill;
    use App\Models\User;
@endphp
<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-14 w-auto fill-current text-gray-800 dark:text-gray-200"/>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('characters.index')" :active="request()->routeIs('characters.index')">
                            {{ __('My Characters') }}
                        </x-nav-link>
                        <x-nav-link :href="route('downtimes.index')" :active="request()->routeIs('downtimes.*')">
                            {{ __('Downtimes') }}
                        </x-nav-link>
                        <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                            {{ __('Events') }}
                        </x-nav-link>
                        @if (Auth::user()->can('access executive menu') || Auth::user()->can('viewSkills', Character::class))
                            <x-dropdown align="left"
                                        contentClasses="py-1 bg-white dark:bg-gray-700 divide-y divide-gray-100">
                                <x-slot name="trigger" class="inline-flex">
                                    <x-nav-link class="cursor-pointer"
                                                :active="request()->routeIs('plotco.*') || request()->routeIs('sysref.*') || request()->routeIs('admin.*')">{{ __('Executive') }}</x-nav-link>
                                </x-slot>
                                <x-slot name="content">
                                    @if (Auth::user()->can('edit downtimes') || Auth::user()->canAny(['viewAll', 'viewSkills'], Character::class))
                                        <div>
                                            @can('viewAll', Character::class)
                                                <x-dropdown-link :href="route('plotco.characters')"
                                                                 :active="request()->routeIs('plotco.characters')">
                                                    {{ __('All Characters') }}
                                                </x-dropdown-link>
                                            @endcan
                                            @can('viewSkills', Character::class)
                                                <x-dropdown-link :href="route('plotco.skills')"
                                                                 :active="request()->routeIs('plotco.skills')">
                                                    {{ __('Skill Breakdown') }}
                                                </x-dropdown-link>
                                            @endcan
                                            @can('edit downtimes')
                                                <x-dropdown-link :href="route('plotco.downtimes')"
                                                                 :active="request()->routeIs('plotco.downtimes*')">
                                                    {{ __('Downtimes') }}
                                                </x-dropdown-link>
                                            @endcan
                                        </div>
                                    @endif
                                    @can('view attendance')
                                        <div>
                                            <x-dropdown-link :href="route('events.all-attendance')"
                                                             :active="request()->routeIs('events.all-attendance')">
                                                {{ __('Event Attendance') }}
                                            </x-dropdown-link>
                                        </div>
                                    @endcan
                                    @can('edit', Skill::class)
                                        <div>
                                            <x-dropdown-link :href="route('sysref.skill-check')"
                                                             :active="request()->routeIs('sysref.skill-check')">
                                                {{ __('Skill Check') }}
                                            </x-dropdown-link>
                                        </div>
                                    @endcan
                                    @can('modify roles')
                                        <div>
                                            <x-dropdown-link :href="route('admin.manage-roles')"
                                                             :active="request()->routeIs('admin.manage-roles')">
                                                {{ __('Manage roles') }}
                                            </x-dropdown-link>
                                        </div>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        @endif
                    @else
                        <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
                            {{ __('Log in') }}
                        </x-nav-link>
                        @if (Route::has('register'))
                            <x-nav-link :href="route('register')" :active="request()->routeIs('register')">
                                {{ __('Register') }}
                            </x-nav-link>
                        @endif
                        <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                            {{ __('Events') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            @auth
                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <span>
                                    {{ Auth::user()->name }}
                                    @if (!Auth::user()->isNameUnique())
                                        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                                    @endif
                                </span>

                                <span class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                                @if (!Auth::user()->isNameUnique())
                                    <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                                @endif
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                                 onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endauth

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('characters.index')"
                                       :active="request()->routeIs('characters.index')">
                    {{ __('My Characters') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('downtimes.index')"
                                       :active="request()->routeIs('downtimes.index')">
                    {{ __('Downtimes') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('events.index')"
                                       :active="request()->routeIs('events.index')">
                    {{ __('Events') }}
                </x-responsive-nav-link>
                @if (Auth::user()->can('edit downtimes') || Auth::user()->canAny(['viewAll', 'viewSkills'], Character::class))
                    <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="px-4">
                            <div
                                class="font-medium text-base text-gray-800 dark:text-gray-200">{{ __('Plot Coordinator') }}</div>
                        </div>
                        @can('viewAll', Character::class)
                            <x-responsive-nav-link :href="route('plotco.characters')"
                                                   :active="request()->routeIs('plotco.characters')">
                                {{ __('All Characters') }}
                            </x-responsive-nav-link>
                        @endcan
                        @can('viewSkills', Character::class)
                            <x-responsive-nav-link :href="route('plotco.skills')"
                                                   :active="request()->routeIs('plotco.skills')">
                                {{ __('Skill Breakdown') }}
                            </x-responsive-nav-link>
                        @endcan
                        @can('edit downtimes')
                            <x-responsive-nav-link :href="route('plotco.downtimes')"
                                                   :active="request()->routeIs('plotco.downtimes')">
                                {{ __('Downtimes') }}
                            </x-responsive-nav-link>
                        @endcan
                    </div>
                @endif
                @can('view attendance')
                    <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="px-4">
                            <div
                                class="font-medium text-base text-gray-800 dark:text-gray-200">{{ __('Secretary') }}</div>
                        </div>
                        <x-responsive-nav-link :href="route('events.all-attendance')"
                                               :active="request()->routeIs('events.all-attendance')">
                            {{ __('Attendance') }}
                        </x-responsive-nav-link>
                    </div>
                @endcan
                @can('edit', Skill::class)
                    <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="px-4">
                            <div
                                class="font-medium text-base text-gray-800 dark:text-gray-200">{{ __('System Referee') }}</div>
                        </div>
                        <x-responsive-nav-link :href="route('sysref.skill-check')"
                                               :active="request()->routeIs('sysref.skill-check')">
                            {{ __('Skill Check') }}
                        </x-responsive-nav-link>
                    </div>
                @endcan
                @can('modify roles')
                    <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ __('Admin') }}</div>
                        </div>
                        <x-responsive-nav-link :href="route('admin.manage-roles')"
                                               :active="request()->routeIs('admin.manage-roles')">
                            {{ __('Manage roles') }}
                        </x-responsive-nav-link>
                    </div>
                @endcan
            @else
                <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                    {{ __('Log in') }}
                </x-responsive-nav-link>
                @if (Route::has('register'))
                    <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('events.index')"
                                       :active="request()->routeIs('events.index')">
                    {{ __('Events') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                        @if (!Auth::user()->isNameUnique())
                            <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                        @endif
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                               onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
