<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>
    </header>

    <div>
        <x-input-label for="name" :value="__('Name')"/>
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                      disabled/>
    </div>

    <div>
        <x-input-label for="pronouns" :value="__('Pronouns')"/>
        <x-text-input id="pronouns" name="pronouns" type="text" class="mt-1 block w-full" :value="old('pronouns', $user->pronouns)"
                      disabled/>
    </div>

    <div>
        <x-input-label for="membership_name" :value="__('Membership Name')"/>
        <x-text-input id="membership_name" name="membership_name" type="text" class="mt-1 block w-full" :value="old('membership_name', $user->membership_name)"
                      disabled/>
    </div>

    <div>
        <x-input-label for="membership_number" :value="__('Membership Number')"/>
        <x-text-input id="membership_number" name="membership_number" type="text" class="mt-1 block w-full" :value="old('membership_number', $user->membership_number)"
                      disabled/>
    </div>

    <div>
        <x-input-label for="email" :value="__('Email')"/>
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                      disabled/>
    </div>
</section>
