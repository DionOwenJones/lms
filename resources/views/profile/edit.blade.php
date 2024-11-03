<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Information -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                Profile Information
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Update your account's profile information and email address.
                            </p>
                        </header>

                        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <div class="flex items-center space-x-6">
                                <div class="shrink-0">
                                    <img class="h-16 w-16 object-cover rounded-full" 
                                         src="{{ auth()->user()->profile_photo_url }}" 
                                         alt="{{ auth()->user()->name }}">
                                </div>
                                <x-inputs.file
                                    name="photo"
                                    label="Photo"
                                    accept="image/*"
                                />
                            </div>

                            <x-inputs.text
                                name="name"
                                label="Name"
                                :value="old('name', $user->name)"
                                required
                                autofocus
                            />

                            <x-inputs.text
                                type="email"
                                name="email"
                                label="Email"
                                :value="old('email', $user->email)"
                                required
                            />

                            <x-inputs.select
                                name="timezone"
                                label="Timezone"
                                :options="$timezones"
                                :value="old('timezone', $user->timezone)"
                            />

                            <div class="flex items-center gap-4">
                                <x-buttons.primary>Save</x-buttons.primary>
                                @if (session('status') === 'profile-updated')
                                    <p class="text-sm text-gray-600">Saved.</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <!-- Update Password -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                Update Password
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Ensure your account is using a long, random password to stay secure.
                            </p>
                        </header>

                        <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('put')

                            <x-inputs.password
                                name="current_password"
                                label="Current Password"
                                autocomplete="current-password"
                            />

                            <x-inputs.password
                                name="password"
                                label="New Password"
                                autocomplete="new-password"
                            />

                            <x-inputs.password
                                name="password_confirmation"
                                label="Confirm Password"
                                autocomplete="new-password"
                            />

                            <div class="flex items-center gap-4">
                                <x-buttons.primary>Save</x-buttons.primary>
                                @if (session('status') === 'password-updated')
                                    <p class="text-sm text-gray-600">Saved.</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <!-- Delete Account -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section class="space-y-6">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                Delete Account
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Once your account is deleted, all of its resources and data will be permanently deleted.
                            </p>
                        </header>

                        <x-buttons.danger
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                        >
                            Delete Account
                        </x-buttons.danger>

                        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                                @csrf
                                @method('delete')

                                <h2 class="text-lg font-medium text-gray-900">
                                    Are you sure you want to delete your account?
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    Once your account is deleted, all of its resources and data will be permanently deleted.
                                </p>

                                <x-inputs.password
                                    name="password"
                                    label="Password"
                                    class="mt-6"
                                    placeholder="Enter your password to confirm"
                                />

                                <div class="mt-6 flex justify-end">
                                    <x-buttons.secondary x-on:click="$dispatch('close')">
                                        Cancel
                                    </x-buttons.secondary>
                                    <x-buttons.danger class="ml-3">
                                        Delete Account
                                    </x-buttons.danger>
                                </div>
                            </form>
                        </x-modal>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 