<x-app-layout>
    <x-slot name="title">{{ __('Changelog') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Changelog') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 22nd April 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: added ability to delete downtime actions before processing.</li>
                    <li>Plot Co: show misc actions in downtime view.</li>
                    <li>Fixed bug which allowed anyone to submit downtime actions regardless of event attendance.</li>
                    <li>Replaced wording about submitting downtime actions with additional notes.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Update: 20th April 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: add downtime reminder email.</li>
                    <li>Plot Co: fixed bug with printing characters.</li>
                    <li>Remove attendance feature from characters.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 17th April 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: fixed event skill breakdown.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Minor updates: 15th April 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: now has event editing permission.</li>
                    <li>Added CTA to downtime announcements.</li>
                    <li>Teachers now get the number of trainees when checking their downtime submission.</li>
                    <li>Empty miscellaneous actions are now removed.</li>
                    <li>Fixed some issues with training course information display.</li>
                    <li>Aligned language on preprocess downtime view.</li>
                    <li>Limited emails when testing to prevent upsetting Mailtrap.</li>
                    <li>Expert Knowledge skills no longer trained by main skill.</li>
                    <li>Fixed potential exploit where a character could double their training from a single course.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 13th April 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Fixed bug with skill training seeding.</li>
                    <li>Fixed timezone display and saving.</li>
                    <li>Fixed bug with skill training costs on downtime submission.</li>
                    <li>Fixed bug with Research Action display.</li>
                    <li>Fixed bug with Misc Action saving and display.</li>
                    <li>Added better notes around teaching and training.</li>
                    <li>Fixed a broken icon.</li>
                    <li>Fixed the email header to use the logo.</li>
                    <li>Improved display of downtime indexes.</li>
                    <li>Adapted downtime processing to handle over-training a skill.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold"><a href="https://github.com/indefinitedevil/stargate-database/pull/48"
                                                     class="underline" target="_blank">Release: 12th April 2025</a></h3>
                <ul class="list-inside list-disc">
                    <li>Executive: combined menu options.</li>
                    <li>Plot Co: added downtime system.</li>
                    <li>Plot Co: added handling of plot changes to characters.</li>
                    <li>Secretary: added event editing system.</li>
                    <li>Secretary: added event attendance system.</li>
                    <li>Secretary: added paid downtimes.</li>
                    <li>Event Runner: added access to skills breakdown view.</li>
                    <li>Sys Ref: added skill description to skill check view.</li>
                    <li>Added Secretary and Event Runner roles.</li>
                    <li>Changed attendance tracking to be attached to users, not characters.</li>
                    <li>Added support for paid downtimes.</li>
                    <li>Added system to track which basic skills can be trained by full skill teaching.</li>
                    <li>Added downtime submission system.</li>
                    <li>Played characters can no longer be deleted.</li>
                    <li>Background skills are now added to trained skills list.</li>
                    <li>Unified layouts to allow for global notices.</li>
                    <li>Added global notice when downtime is open.</li>
                    <li>Added success messaging.</li>
                    <li>Added success and info states for global notices.</li>
                    <li>Customised emails with Stargate branding.</li>
                    <li>Added inactive state for characters.</li>
                    <li>Improved skill description display universally.</li>
                    <li>Added handling of resuscitation.</li>
                    <li>Reorganised character actions.</li>
                    <li>Aligned gap styling on character pages.</li>
                    <li>Fixed timezone issues.</li>
                    <li>Updated rules in line with the 1.2 update.
                        <ul class="list-inside list-disc pl-4">
                            <li>Updated skill and feat descriptions.</li>
                            <li>Character creation months are now adjusted in line with lowest trained months.</li>
                            <li>Added Self-Defense skill.</li>
                            <li>Added Primitive Weapons as alternate prerequisite for Accuracy Training.</li>
                            <li>Updated printed character sheet with new information on Critical/bleeding.</li>
                        </ul>
                    </li>
                </ul>
                <p class="text-sm">Tested by Ben B, Fraser, Kat, and Orev</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold"><a
                        href="https://github.com/indefinitedevil/stargate-database/pull/40" class="underline"
                        target="_blank">Update: 10th March 2025</a></h3>
                <ul class="list-inside list-disc">
                    <li>Added facility to let players edit their characters after approval.</li>
                    <li>Bug fixes related to short names.</li>
                    <li>Added extra text around purpose of short names.</li>
                    <li>Fixed confirm dialogs.</li>
                </ul>
                <p class="text-sm">Tested by Orev</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Update: 24th February 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Applied new SEF logo made by <a href="https://www.etsy.com/uk/shop/CharlieTeesTrove"
                                                        target="_blank"
                                                        class="underline">Charlie</a> based on the initial
                        design by Mark.
                    </li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold"><a
                        href="https://github.com/indefinitedevil/stargate-database/pull/36" class="underline"
                        target="_blank">Update: 8th February 2025</a></h3>
                <ul class="list-inside list-disc">
                    <li>Updated character links to be include character name (old links will still work).</li>
                    <li>Fixed <a href="https://github.com/indefinitedevil/stargate-database/issues/34"
                                 target="_blank"
                                 class="underline">issue</a> with scaling costs display for approved characters.
                    </li>
                    <li>Added random name generator to character create/edit screen <a
                            href="https://github.com/indefinitedevil/stargate-database/issues/32"
                            target="_blank" class="underline">at request</a>.
                    </li>
                    <li>Fixed some validation and made minor wording changes.</li>
                    <li>Plot Co: enabled editing of ranks.</li>
                    <li>Plot Co: add character links to skills breakdown.</li>
                    <li>Hide former ranks from display if new rank assigned.</li>
                    <li>Add short name editing and display.</li>
                    <li>Implemented soft-deletion of users.</li>
                    <li>Add wounded and bleeding state notes to character sheet.</li>
                    <li>Change buttons to use a combined icon/text appearance and unify appearance across site.</li>
                </ul>
                <p class="text-sm">Tested by Debi</p>
            </div>

            <div>
                <h3 class="text-lg font-semibold">Bugfix: 13th January 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: Fixed skills breakdown to only have one row per character and include background
                        skills.
                    </li>
                    <li>Updated privacy policy to include contact email address.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold"><a
                        href="https://github.com/indefinitedevil/stargate-database/pull/28" class="underline"
                        target="_blank">Update: 11th January 2025</a></h3>
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
</x-app-layout>
