<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Course Header -->
                <div class="relative h-96">
                    <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center">
                        <div class="px-8">
                            <h1 class="text-4xl font-bold text-white">{{ $course->title }}</h1>
                            <p class="mt-4 text-lg text-white">{{ $course->description }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Course Info -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="col-span-2">
                            <!-- Course Overview -->
                            <div class="prose max-w-none">
                                <h2 class="text-2xl font-semibold">Course Overview</h2>
                                <p>{{ $course->description }}</p>
                                
                                <!-- Course Curriculum -->
                                <h3 class="text-xl font-semibold mt-8">Course Curriculum</h3>
                                <div class="mt-4 space-y-4">
                                    @foreach($course->lessons as $lesson)
                                        <div class="border rounded-lg p-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h4 class="font-medium">{{ $lesson->title }}</h4>
                                                    <p class="text-sm text-gray-600">{{ $lesson->duration }} minutes</p>
                                                </div>
                                                @auth
                                                    @if($lesson->completedByUser(auth()->user()))
                                                        <span class="text-green-600">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </span>
                                                    @endif
                                                @endauth
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Course Sidebar -->
                        <div>
                            <div class="bg-gray-50 rounded-lg p-6 sticky top-6">
                                <div class="text-center">
                                    <span class="text-3xl font-bold">
                                        @if($course->price == 0)
                                            Free
                                        @else
                                            £{{ number_format($course->price, 2) }}
                                        @endif
                                    </span>
                                </div>

                                <div class="mt-6 space-y-4">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="ml-2">{{ $course->duration }} minutes</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="ml-2">{{ ucfirst($course->level) }} Level</span>
                                    </div>
                                </div>

                                @auth
                                    @if(auth()->user()->courses->contains($course))
                                        <a href="{{ route('courses.learn', $course) }}" 
                                           class="mt-6 block w-full text-center bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                            Continue Learning
                                        </a>
                                    @else
                                        <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="mt-6 w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                                Enroll Now
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="mt-6 block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                        Login to Enroll
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 