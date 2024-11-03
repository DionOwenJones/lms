<x-admin-layout>
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">Create New User</h2>
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Back to Users
                </a>
            </div>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                           required>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                           required>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                           required>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirm Password
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                           required>
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                        <option value="business" {{ old('role') === 'business' ? 'selected' : '' }}>Business</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div id="business-section" class="hidden">
                    <label for="business_id" class="block text-sm font-medium text-gray-700">Business</label>
                    <select name="business_id" id="business_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Select Business</option>
                        @foreach($businesses as $business)
                            <option value="{{ $business->id }}" {{ old('business_id') == $business->id ? 'selected' : '' }}>
                                {{ $business->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const roleSelect = document.getElementById('role');
        const businessSection = document.getElementById('business-section');

        function toggleBusinessSection() {
            businessSection.classList.toggle('hidden', roleSelect.value !== 'business');
        }

        roleSelect.addEventListener('change', toggleBusinessSection);
        toggleBusinessSection(); // Initial state
    </script>
    @endpush
</x-admin-layout> 