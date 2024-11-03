<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-8">
                <div class="p-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Welcome to Our Learning Platform</h1>
                    <p class="text-lg text-gray-600 mb-6">Discover courses that help you grow professionally and personally.</p>
                    <a href="{{ route('courses.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                        Browse Courses
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Featured Courses -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Featured Courses</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredCourses ?? [] as $course)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $course->title }}</h3>
                                <p class="text-gray-600 mb-4 line-clamp-2">{{ $course->description }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-blue-600 font-semibold">{{ $course->formatted_price }}</span>
                                    <a href="{{ route('courses.show', $course) }}" 
                                       class="text-blue-600 hover:text-blue-700 font-medium">
                                        Learn More
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Categories -->
            <div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Browse by Category</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($categories ?? [] as $category)
                        <a href="{{ route('courses.index', ['category' => $category->slug]) }}" 
                           class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $category->name }}</h3>
                            <p class="text-gray-600 text-sm">{{ $category->courses_count }} courses</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 