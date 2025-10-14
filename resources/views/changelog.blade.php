<x-app-layout>
    <x-slot name="title">{{ __('Changelog') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Changelog') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
            <div>
                <h3 class="text-lg font-semibold">Update: 14th October 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Secretary: added permission to manage memberships.</li>
                    <li>Sys Ref: added rules 1.4 update.</li>
                    <li>Added beginnings of membership verification system.</li>
                    <li>Added membership name to profiles.</li>
                    <li>Character logs now clearly show bonus from attending a training course.</li>
                    <li>Renamed character "type" to "archetype".</li>
                    <li>Removed level from skill names.</li>
                    <li>Made some messages more readable.</li>
                    <li>Updated skill coverage language.</li>
                    <li>Added additional logging.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 7th October 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Researcher: fixed a bug which prevented new research projects from being created.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 30th September 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Fixed an issue where using research actions for upkeep caused a bug.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 21st September 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: fixed issue with delete buttons causing saves to error.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 3rd September 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Fixed research project skill names on downtime submission.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 2nd September 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Fixed research project info on downtime submission.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Update: 1st September 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: added lowest-training character name to info box.</li>
                    <li>Secretary: added toggle to hide non-booked people from attendance screen.</li>
                    <li>Researcher: allow deletion of pending projects.</li>
                    <li>Altered design philosophy to be closer to holy grail design.</li>
                    <li>Added warning for missing skill specialties.</li>
                    <li>Refactored code for better practices.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 27th August 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Fixed research project view for non-approved projects.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 26th August 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Fixed research project ordering and increased per-page limit.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 24th August 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: made division not mandatory.</li>
                    <li>Fixed bug with scaling-cost skills for new characters.</li>
                    <li>Refactored skill cost calculations for better caching.</li>
                    <li>Excluded NPCs from skill coverage.</li>
                    <li>Cleaned up code.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Update: 23rd August 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Added organisation chart.</li>
                    <li>Added feature for players to see skill coverage across characters.</li>
                    <li>Added missing team view.</li>
                    <li>Fixed bug with events disappearing from indexes on the final day of the event.</li>
                    <li>Refactored controllers and views.</li>
                    <li>Improved class loading.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Update: 5th August 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: changed catch-up XP display.</li>
                    <li>Sys Ref: changed catch-up XP display.</li>
                    <li>Changed catch-up XP to be calculated based on the entirety of training, not just downtime
                        training.
                    </li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Update: 4th August 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: added management of teams, departments, and divisions.</li>
                    <li>Command: added management of teams, departments, and divisions.</li>
                    <li>Added new role: Command.</li>
                    <li>Reorganised research project view.</li>
                    <li>Added ordered lists to the markdown processor.</li>
                    <li>Added teams, departments, and divisions to characters.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 2nd August 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: fixed issues with downtime processing and response saving.</li>
                    <li>Plot Co: fixed issues with long-text research responses in emails.</li>
                    <li>Fixed bug with scaling-cost skills not scaling in cost once on character sheet.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Update: 1st August 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: increased response box height.</li>
                    <li>Secretary: improved event attendance screen for mobile and desktop.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Update: 29th July 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: updated downtime user count to not include crew without characters.</li>
                    <li>Sys Ref: updated Leadership to apply training benefits to the extra person upgrade.</li>
                    <li>Removed seeder references from migrations. Seeders should be run on-demand to avoid clashes with
                        migrations.
                    </li>
                    <li>Added note about where to add specialties to the add skills form.</li>
                    <li>Fixed Leadership skill to properly count the number of people you can affect.</li>
                    <li>Fixed a repeating skill description in the edit skills form.</li>
                    <li>Bug fixes!</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 20th July 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Fixed issue where two teachers could not benefit from each other's courses.</li>
                    <li>Limited downtime actions notes and responses to 2000 characters.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 16th July 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Updated log tables.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Update: 13th July 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Updated research project views for better clarity of skill spread.</li>
                    <li>Show skill checks in downtime view for better clarity.</li>
                    <li>Improve formatting.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Update: 12th July 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: make character archetype editable after approval.</li>
                    <li>Added skill selection to research actions.</li>
                    <li>Hid System skills from list of skills for teaching/researching.</li>
                    <li>Updated error appearance on downtime submissions.</li>
                    <li>Updated notes placeholders.</li>
                    <li>Use base skill name on training course list.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 11th July 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: added view as player option.</li>
                    <li>Fixed skill upkeep processing errors.</li>
                    <li>Made research projects only available for <em>completed</em> skills.</li>
                    <li>Changed view of project researchers.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 7th July 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Fixed downtime processing errors.</li>
                    <li>Refined downtime processed email.</li>
                    <li>Fixed Coderabbit nitpicks.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 9th July 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Added skill specialties to research projects.</li>
                    <li>Made downtime open/closed language better for downtimes in the future.</li>
                    <li>Fixed Coderabbit nitpicks.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Update: 4th July 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Sys Ref: rules 1.3.1 update.</li>
                    <li>Sys Ref: added abilities to skill check view.</li>
                    <li>Added explanation to help text about catchup XP calculations.</li>
                    <li>Added per-event trackers for feats.</li>
                    <li>Added percentage time discount note for cards.</li>
                    <li>Added list of abilities to character sheets.</li>
                    <li>Removed unnecessary code.</li>
                    <li>Changed reset code to allow for plot updates to remain after reset.</li>
                    <li>Made changes as per suggestions from AI code review.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Update: 1st July 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: added editing and approval capacity for research projects.</li>
                    <li>Plot Co: added facility for personal responses to downtimes.</li>
                    <li>Plot Co: added character traits.</li>
                    <li>Plot Co: added general response field to downtimes.</li>
                    <li>Plot Co: track changes to character traits.</li>
                    <li>Researcher: added editing capacity for research projects.</li>
                    <li>Added research projects.</li>
                    <li>Added research subject action.</li>
                    <li>Added research projects to downtime submissions.</li>
                    <li>Added research projects to downtime email.</li>
                    <li>Added personal action responses to downtime email.</li>
                    <li>Cleaned up code to combine view routes.</li>
                    <li>Fixed bugs with database duplication.</li>
                    <li>Cleaned up button displays.</li>
                    <li>Paginated log displays.</li>
                    <li>Made changes as per suggestions from AI code review.</li>
                </ul>
                <p class="text-sm">Tested by Kath, Debi, James, and others</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 29th June 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: allow for hidden log notes.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Update: 23rd June 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Admin: tidied manage roles screen.</li>
                    <li>Secretary: updated attendance lists to show event roles.</li>
                    <li>Plot Co: updated training/catchup XP display.</li>
                    <li>Plot Co: fixed bug in viewing downtime actions.</li>
                    <li>Sys Ref: updated catchup XP calculations to be based solely on downtime training, ignoring
                        characters who have not submitted a downtime.
                    </li>
                    <li>Sys Ref: added catchup XP numbers to Skill Check screen.</li>
                    <li>Sys Ref: rules 1.3 update.</li>
                    <li>Event Runner: removed dedicated role.</li>
                    <li>Event runners now have access to the skill breakdown based on their attendance role for future
                        events.
                    </li>
                    <li>Updated feat display to be clearer regarding uses per day/event and Vigor costs.</li>
                    <li>Added a banner to make it clear when on a test site.</li>
                    <li>Changed "miscellaneous actions" to "personal actions" in downtime and added explanatory text.
                    </li>
                    <li>Fixed bug which blocked character deletions.</li>
                    <li>Made changes as per suggestions from AI code review.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 16th June 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Secretary: fixed bug with access to executive screen.</li>
                    <li>Added <a href="/roles" class="underline">roles page</a> to show the different available roles.
                    </li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Update: 19th May 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: allow change of user on characters to allow for NPCs to be assigned to plot co user.
                    </li>
                    <li>Plot Co: organise user lists by type.</li>
                    <li>Plot Co: allow direct creation and editing of character logs.</li>
                    <li>Plot Co: removed ability to add completed skills to characters without creating a log.</li>
                    <li>Plot Co: added section for "other abilities" to characters.</li>
                    <li>Track ID of user making changes to characters.</li>
                    <li>Add plot coordinator user for system use.</li>
                    <li>Changed Endurance Training additions from E1 to be permanent Body changes instead.</li>
                    <li>Changed attendance tracking to remove temporary Body/Vigor.</li>
                    <li>Refactored inline scripts to be shared files.</li>
                    <li>Had CodeRabbit do a code review and fixed some bugs as a result.</li>
                    <li>Corrected the parameters for when skill upkeep is required.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 30th April 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Fixed bug with skill completion during downtime processing.</li>
                    <li>Fixed display bug in downtime processing emails.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 28th April 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: added count of submitted downtimes.</li>
                    <li>Plot Co: added button for editing downtime submissions.</li>
                    <li>Fixed bug with setting played status.</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Bugfix: 22nd April 2025</h3>
                <ul class="list-inside list-disc">
                    <li>Plot Co: added ability to delete downtime actions before processing.</li>
                    <li>Plot Co: show misc actions in downtime view.</li>
                    <li>Secretary: added labels for accessibility.</li>
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
                    <li>Fixed Botch Job showing up erroneously on bulk prints.</li>
                    <li>Changed formatting of print skills.</li>
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
                                 class="underline">issue</a> with scaling-costs display for approved characters.
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
