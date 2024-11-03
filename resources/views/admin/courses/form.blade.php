<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($course) ? 'Edit Course' : 'Create New Course' }}
        </h2>
    </x-slot>

    <div class="bg-white shadow-sm rounded-lg">
        <form action="{{ isset($course) ? route('admin.courses.update', $course) : route('admin.courses.store') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="p-6">
            @csrf
            @if(isset($course))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', $course->title ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" 
                              id="description" 
                              rows="4"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $course->description ?? '') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price (£)</label>
                        <input type="number" 
                               name="price" 
                               id="price" 
                               step="0.01"
                               value="{{ old('price', $course->price ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                        <input type="number" 
                               name="duration" 
                               id="duration"
                               value="{{ old('duration', $course->duration ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('duration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="level" class="block text-sm font-medium text-gray-700">Level</label>
                        <select name="level" 
                                id="level"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="beginner" @selected(old('level', $course->level ?? '') == 'beginner')>Beginner</option>
                            <option value="intermediate" @selected(old('level', $course->level ?? '') == 'intermediate')>Intermediate</option>
                            <option value="advanced" @selected(old('level', $course->level ?? '') == 'advanced')>Advanced</option>
                        </select>
                        @error('level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="categories" class="block text-sm font-medium text-gray-700">Categories</label>
                    <select name="categories[]" 
                            id="categories" 
                            multiple
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                @selected(isset($course) && $course->categories->contains($category->id))>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('categories')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="thumbnail" class="block text-sm font-medium text-gray-700">Thumbnail</label>
                    <input type="file" 
                           name="thumbnail" 
                           id="thumbnail"
                           accept="image/*"
                           class="mt-1 block w-full">
                    @if(isset($course) && $course->thumbnail)
                        <div class="mt-2">
                            <img src="{{ Storage::url($course->thumbnail) }}" 
                                 alt="Current thumbnail" 
                                 class="h-32 w-32 object-cover rounded">
                        </div>
                    @endif
                    @error('thumbnail')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" 
                            id="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="draft" @selected(old('status', $course->status ?? '') == 'draft')>Draft</option>
                        <option value="published" @selected(old('status', $course->status ?? '') == 'published')>Published</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.courses.index') }}" 
                       class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        {{ isset($course) ? 'Update Course' : 'Create Course' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout> 