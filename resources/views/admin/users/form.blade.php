<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($user) ? 'Edit User' : 'Create New User' }}
        </h2>
    </x-slot>

    <div class="bg-white shadow-sm rounded-lg">
        <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" 
              method="POST" 
              class="p-6">
            @csrf
            @if(isset($user))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $user->name ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" 
                           name="email" 
                           id="email"
                           value="{{ old('email', $user->email ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        {{ isset($user) ? 'New Password (leave blank to keep current)' : 'Password' }}
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" 
                            id="role"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="user" @selected(old('role', $user->role ?? '') == 'user')>User</option>
                        <option value="business" @selected(old('role', $user->role ?? '') == 'business')>Business Owner</option>
                        <option value="admin" @selected(old('role', $user->role ?? '') == 'admin')>Admin</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="business_id" class="block text-sm font-medium text-gray-700">Business (Optional)</label>
                    <select name="business_id" 
                            id="business_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">No Business</option>
                        @foreach($businesses as $business)
                            <option value="{{ $business->id }}" 
                                    @selected(old('business_id', $user->business_id ?? '') == $business->id)>
                                {{ $business->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('business_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        {{ isset($user) ? 'Update User' : 'Create User' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout> 