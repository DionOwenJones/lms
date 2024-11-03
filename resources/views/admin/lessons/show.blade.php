<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">{{ $lesson->title }}</h2>
                    <p class="text-sm text-gray-600">Part of course: {{ $lesson->course->title }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}" 
                       class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Edit Lesson
                    </a>
                    <a href="{{ route('admin.courses.show', $course) }}" 
                       class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        Back to Course
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Main Content -->
                        <div class="md:col-span-2">
                            <!-- Video Section -->
                            @if($lesson->video_url)
                                <div class="aspect-w-16 aspect-h-9 mb-6">
                                    <iframe src="{{ $lesson->video_url }}" 
                                            class="w-full h-full rounded-lg"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen></iframe>
                                </div>
                            @endif

                            <!-- Lesson Content -->
                            <div class="prose max-w-none">
                                <h3 class="text-xl font-semibold mb-4">Lesson Content</h3>
                                {{ $lesson->content }}
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div>
                            <!-- Lesson Details -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold mb-4">Lesson Details</h3>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Duration</p>
                                        <p class="text-lg font-medium">{{ $lesson->duration }} minutes</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Order in Course</p>
                                        <p class="text-lg font-medium">Lesson {{ $lesson->order }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Student Progress</p>
                                        <p class="text-lg font-medium">
                                            {{ $lesson->userProgress()->where('completed', true)->count() }} 
                                            students completed
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="mt-6 bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                                <div class="space-y-3">
                                    <a href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}" 
                                       class="block w-full text-center bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                        Edit Lesson
                                    </a>
                                    <form action="{{ route('admin.courses.lessons.destroy', [$course, $lesson]) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this lesson?');"
                                          class="block w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full text-center bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                            Delete Lesson
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 