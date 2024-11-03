<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Course Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <form action="{{ route('courses.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category" class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Level</label>
                        <select name="level" class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="">All Levels</option>
                            <option value="beginner" @selected(request('level') == 'beginner')>Beginner</option>
                            <option value="intermediate" @selected(request('level') == 'intermediate')>Intermediate</option>
                            <option value="advanced" @selected(request('level') == 'advanced')>Advanced</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price Range</label>
                        <select name="price" class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="">Any Price</option>
                            <option value="free" @selected(request('price') == 'free')>Free</option>
                            <option value="paid" @selected(request('price') == 'paid')>Paid</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Course Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <h2 class="text-xl font-semibold text-gray-900">{{ $course->title }}</h2>
                                <span class="px-2 py-1 text-sm rounded-full 
                                    {{ $course->level === 'beginner' ? 'bg-green-100 text-green-800' : 
                                       ($course->level === 'intermediate' ? 'bg-yellow-100 text-yellow-800' : 
                                        'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($course->level) }}
                                </span>
                            </div>
                            <p class="mt-2 text-gray-600 line-clamp-2">{{ $course->description }}</p>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-lg font-bold text-gray-900">
                                    @if($course->price == 0)
                                        Free
                                    @else
                                        £{{ number_format($course->price, 2) }}
                                    @endif
                                </span>
                                <span class="text-sm text-gray-600">
                                    {{ $course->duration }} minutes
                                </span>
                            </div>
                            <a href="{{ route('courses.show', $course) }}" 
                               class="mt-4 block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                View Course
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</x-app-layout> 