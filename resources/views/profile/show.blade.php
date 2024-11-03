<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Profile Information') }}
                            </h2>
                        </header>

                        <div class="mt-6 space-y-6">
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <p class="mt-1 text-sm text-gray-600">{{ $user->name }}</p>
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <p class="mt-1 text-sm text-gray-600">{{ $user->email }}</p>
                            </div>

                            <div class="flex items-center gap-4">
                                <a href="{{ route('profile.edit') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    {{ __('Edit Profile') }}
                                </a>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 