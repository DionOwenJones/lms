<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Create New Course</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Add a new course to your learning management system.
                        </p>
                    </div>

                    <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Course Basic Information -->
                        <div class="grid grid-cols-1 gap-6 mt-4">
                            <div>
                                <x-input-label for="title" value="Course Title" />
                                <x-text-input id="title" 
                                             name="title" 
                                             type="text"
                                             class="mt-1 block w-full" 
                                             value="{{ old('title') }}" 
                                             required />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="description" value="Description" />
                                <textarea id="description"
                                          name="description"
                                          rows="4"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          required>{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="category_id" value="Category" />
                                    <select id="category_id"
                                            name="category_id"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="price" value="Price" />
                                    <x-text-input id="price"
                                                 name="price"
                                                 type="number"
                                                 step="0.01"
                                                 class="mt-1 block w-full"
                                                 value="{{ old('price') }}"
                                                 required />
                                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="duration" value="Duration (hours)" />
                                    <x-text-input id="duration"
                                                 name="duration"
                                                 type="number"
                                                 step="0.5"
                                                 class="mt-1 block w-full"
                                                 value="{{ old('duration') }}"
                                                 required />
                                    <x-input-error :messages="$errors->get('duration')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="thumbnail" value="Course Thumbnail" />
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="thumbnail" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>Upload a file</span>
                                                <input id="thumbnail" name="thumbnail" type="file" class="sr-only" accept="image/*">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('thumbnail')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Course Modules -->
                        <div class="mt-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Course Modules</h3>
                                <button type="button" 
                                        onclick="addModule()"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                    Add Module
                                </button>
                            </div>

                            <div id="modules-container">
                                <!-- Modules will be added here dynamically -->
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <x-secondary-button type="button" class="mr-3" onclick="window.history.back()">
                                Cancel
                            </x-secondary-button>
                            <x-primary-button>
                                Create Course
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let moduleCount = 0;

        function addModule() {
            const container = document.getElementById('modules-container');
            const moduleHtml = `
                <div class="module-item bg-gray-50 p-4 rounded-lg mb-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="flex justify-between items-center">
                            <h4 class="text-md font-medium text-gray-900">Module ${moduleCount + 1}</h4>
                            <button type="button" onclick="this.closest('.module-item').remove()" class="text-red-600 hover:text-red-900">
                                Remove
                            </button>
                        </div>
                        <div>
                            <x-input-label value="Module Title" />
                            <x-text-input name="modules[${moduleCount}][title]" 
                                         type="text"
                                         class="mt-1 block w-full" 
                                         required />
                        </div>
                        <div>
                            <x-input-label value="Module Description" />
                            <textarea name="modules[${moduleCount}][description]"
                                      rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      required></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Duration (minutes)" />
                                <x-text-input name="modules[${moduleCount}][duration]"
                                             type="number"
                                             class="mt-1 block w-full"
                                             required />
                            </div>
                            <div>
                                <x-input-label value="Order" />
                                <x-text-input name="modules[${moduleCount}][order]"
                                             type="number"
                                             value="${moduleCount + 1}"
                                             class="mt-1 block w-full"
                                             required />
                            </div>
                        </div>
                        <div>
                            <x-input-label value="Content File" />
                            <input type="file" 
                                   name="modules[${moduleCount}][content]"
                                   class="mt-1 block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-blue-50 file:text-blue-700
                                          hover:file:bg-blue-100" />
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', moduleHtml);
            moduleCount++;
        }

        // Add first module by default
        document.addEventListener('DOMContentLoaded', function() {
            addModule();
        });
    </script>
    @endpush
</x-app-layout> 