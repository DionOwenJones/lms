<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <h2 class="text-2xl font-semibold text-gray-900">Course Details</h2>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.courses.edit', $course) }}" 
                       class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Edit Course
                    </a>
                    <a href="{{ route('admin.courses.index') }}" 
                       class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        Back to Courses
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <!-- Course Header -->
                <div class="relative h-96">
                    <img src="{{ Storage::url($course->thumbnail) }}" 
                         alt="{{ $course->title }}" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center">
                        <div class="px-8">
                            <h1 class="text-4xl font-bold text-white">{{ $course->title }}</h1>
                            <div class="mt-4 flex items-center space-x-4">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold 
                                    {{ $course->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                                <span class="text-white">{{ $course->duration }} minutes</span>
                                <span class="text-white">£{{ number_format($course->price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Course Info -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="col-span-2">
                            <!-- Course Overview -->
                            <div class="prose max-w-none">
                                <h3 class="text-xl font-semibold mb-4">Course Overview</h3>
                                <p>{{ $course->description }}</p>
                            </div>

                            <!-- Lessons -->
                            <div class="mt-8">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-semibold">Lessons</h3>
                                    <a href="{{ route('admin.courses.lessons.create', $course) }}" 
                                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                        Add Lesson
                                    </a>
                                </div>

                                <div class="space-y-4">
                                    @forelse($course->lessons as $lesson)
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <h4 class="font-medium">{{ $lesson->title }}</h4>
                                                    <p class="text-sm text-gray-600">Duration: {{ $lesson->duration }} minutes</p>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}" 
                                                       class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                    <form action="{{ route('admin.courses.lessons.destroy', [$course, $lesson]) }}" 
                                                          method="POST" 
                                                          class="inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this lesson?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-gray-500 text-center py-4">No lessons added yet.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div>
                            <!-- Course Stats -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold mb-4">Course Statistics</h3>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Enrolled Students</p>
                                        <p class="text-2xl font-semibold">{{ $course->users()->count() }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Total Lessons</p>
                                        <p class="text-2xl font-semibold">{{ $course->lessons()->count() }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Categories</p>
                                        <div class="mt-1 flex flex-wrap gap-2">
                                            @foreach($course->categories as $category)
                                                <span class="px-2 py-1 bg-gray-200 rounded-full text-sm">
                                                    {{ $category->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="mt-6 bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                                <div class="space-y-3">
                                    <a href="{{ route('admin.courses.lessons.create', $course) }}" 
                                       class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                        Add New Lesson
                                    </a>
                                    <a href="{{ route('admin.courses.edit', $course) }}" 
                                       class="block w-full text-center bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                        Edit Course Details
                                    </a>
                                    <form action="{{ route('admin.courses.destroy', $course) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this course?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full text-center bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                            Delete Course
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