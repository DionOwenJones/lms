<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="mb-6 flex justify-between items-center">
                        <h2 class="text-2xl font-semibold text-gray-900">Add New Lesson to: {{ $course->title }}</h2>
                        <a href="{{ route('admin.courses.show', $course) }}" 
                           class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                            Back to Course
                        </a>
                    </div>

                    <form action="{{ route('admin.courses.lessons.store', $course) }}" 
                          method="POST" 
                          class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Lesson Title</label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Content -->
                            <div>
                                <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                                <textarea name="content" id="content" rows="6"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          required>{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Video URL -->
                            <div>
                                <label for="video_url" class="block text-sm font-medium text-gray-700">Video URL</label>
                                <input type="url" name="video_url" id="video_url" value="{{ old('video_url') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('video_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Duration -->
                                <div>
                                    <label for="duration" class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                                    <input type="number" name="duration" id="duration" value="{{ old('duration') }}"
                                           min="1"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           required>
                                    @error('duration')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Order -->
                                <div>
                                    <label for="order" class="block text-sm font-medium text-gray-700">Lesson Order</label>
                                    <input type="number" name="order" id="order" 
                                           value="{{ old('order', $nextOrder) }}"
                                           min="1"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           required>
                                    @error('order')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.courses.show', $course) }}" 
                               class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Add Lesson
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 