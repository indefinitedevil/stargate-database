<x-app-layout>
    <x-slot name="title">{{ __('Changelog') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Changelog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                    <div>
                        <h3 class="text-lg font-semibold">Update: 24th February 2025</h3>
                        <ul class="list-inside list-disc">
                            <li>Applied new SEF logo made by <a href="https://www.etsy.com/uk/shop/CharlieTeesTrove" class="underline">Charlie</a> based on the initial design by Mark.</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold"><a
                                href="https://github.com/indefinitedevil/stargate-database/pull/36" class="underline">Update:
                                8th February 2025</a></h3>
                        <ul class="list-inside list-disc">
                            <li>Updated character links to be include character name (old links will still work).</li>
                            <li>Fixed <a href="https://github.com/indefinitedevil/stargate-database/issues/34"
                                         class="underline">issue</a> with scaling costs display for approved characters.
                            </li>
                            <li>Added random name generator to character create/edit screen <a
                                    href="https://github.com/indefinitedevil/stargate-database/issues/32"
                                    class="underline">at request</a>.
                            </li>
                            <li>Fixed some validation and made minor wording changes.</li>
                            <li>Plot Co: enabled editing of ranks.</li>
                            <li>Plot Co: add character links to skills breakdown.</li>
                            <li>Hide former ranks from display if new rank assigned.</li>
                            <li>Add short name editing and display.</li>
                            <li>Implemented soft-deletion of users.</li>
                            <li>Add wounded and bleeding state notes to character sheet.</li>
                            <li>Change buttons to use a combined icon/text appearance and unify appearance across
                                site.
                            </li>
                        </ul>
                        <p class="text-sm">Tested by Debi</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold">Bugfix: 13th January 2025</h3>
                        <ul class="list-inside list-disc">
                            <li>Plot Co: Fixed skills breakdown to only have one row per character and include
                                background skills.
                            </li>
                            <li>Updated privacy policy to include contact email address.</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold"><a
                                href="https://github.com/indefinitedevil/stargate-database/pull/28" class="underline">Update:
                                11th January 2025</a></h3>
                        <ul class="list-inside list-disc">
                            <li>Added changelog</li>
                            <li>Added mobile improvements</li>
                            <li>Added expandable descriptions for skills and feats to character view</li>
                            <li>Updated text field validation to match lengths</li>
                            <li>Added primary/secondary typing to characters</li>
                            <li>Added hero/scoundrel typing to characters</li>
                            <li>Added notice if your name is not unique</li>
                            <li>Admin: Added ability to manage roles</li>
                            <li>Plot Co: Added per-event breakdown to skills breakdown view</li>
                            <li>Plot Co: Added notification for characters becoming ready for approval</li>
                            <li>Plot Co: Added note to show who a character belongs to</li>
                            <li>Add player name to print sheet</li>
                            <li>Add print skills/feats view to characters</li>
                            <li>Improve mobile navigation and styling</li>
                            <li>Add favicon</li>
                            <li>Bug fixes</li>
                        </ul>
                        <p class="text-sm">Tested by Gregg</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold">Release: 5th January 2025</h3>
                        <ul class="list-inside list-disc">
                            <li>Initial release</li>
                            <li>Emergency bug fixes</li>
                            <li>Correcting Point Man cost</li>
                        </ul>
                        <p class="text-sm">Tested by Kat and Topper; feat text entered by Ki</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
