@php
    use App\Helpers\CharacterHelper;
@endphp
<section class="space-y-2">
    <p>
        Welcome to the Stargate character database.
        For those of you who are new, this is where you can create and manage your characters.
    </p>
    <p>
        While this system does its best to model the rules in their entirety, it is recommended that you reference the
        rulebook when creating your character.
    </p>
    <p>
        That said, this information is provided to help you in getting started.
        If you have any questions, please don't hesitate to ask.
    </p>
    <h3 class="text-lg font-bold mt-3">Guidance</h3>
    <ol class="list-decimal list-inside space-y-1">
        <li>
            Start off by going to the <a class="underline" href="{{ route('characters.index') }}">my characters
                section</a>.
        </li>
        <li>
            Click on the "<a class="underline" href="{{ route('characters.create') }}" target="_blank">Create</a>"
            button.
        </li>
        <li>
            Fill in the form with the character's details.
            <ul class="list-inside list-disc pl-4">
                <li>
                    Name and Background are the only required fields on this form.
                </li>
                <li>
                    Providing a character history and other information may help inform the plot cos and event runners.
                </li>
            </ul>
        </li>
        <li>
            Now you've created your character, click their name to enter the character view screen.
        </li>
        <li>
            Click on the "<i class="fa-solid fa-pen-to-square"></i> <span
                class="sm:hidden">{{ __('Edit Skills') }}</span><span class="hidden sm:inline">{{ __('Skills') }}</span>"
            button.
            <ul class="list-disc list-inside pl-4">
                <li>
                    This will take you to the skills editor screen.
                </li>
                <li>
                    Here you can select skills from the dropdown at the bottom and add them to your character.
                </li>
            </ul>
        </li>
        <li>
            Add skills to your character.
            <ul class="list-inside list-disc pl-4">
                <li>
                    You can only add one skill at a time.
                </li>
                <li>
                    You can only see skills that are available to your character.
                </li>
                <li class="pl-4">
                    If a desired skill has a prerequisite, you must add the prerequisite first.
                </li>
                <li>
                    You have <span class="underline decoration-dashed underline-offset-4" title="base 36 + {{ CharacterHelper::getCatchupXP() }} catchup">{{ 36 + CharacterHelper::getCatchupXP() }} months</span> to spend on skills and all of
                    them must be used.
                    A running count of how many you have used will be shown under your trained skills.
                </li>
                <li>
                    Mark skills as completed if you are buying the full skill, or leave it unchecked if you are only
                    partially investing into it.
                </li>
                <li>
                    You can have one unfinished skill at the end of character creation - any remaining months will be
                    assigned to it.
                </li>
            </ul>
        </li>
        <li>
            Once you're happy, click the "<i class="fa-solid fa-check"></i> <span
                class="sm:hidden"> {{ __('Ready for approval') }}</span><span
                class="hidden sm:inline">{{ __('Ready') }}</span>" button and the plot co will look over your sheet.
            <ul class="list-inside list-disc pl-4">
                <li>
                    You can still make changes to your character after this point up until your character is approved,
                    but the limit of one unfinished skill still applies, and adding additional skills will prevent the
                    plot co from approving your character.
                    [This is a system limitation that the plot co cannot override.]
                </li>
            </ul>
        </li>
        <li>
            If there are any issues, they will be raised with you.
            Otherwise you will get an email confirming your character is approved.
        </li>
    </ol>
</section>
