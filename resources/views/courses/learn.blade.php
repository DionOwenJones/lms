<x-app-layout>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar with lesson list -->
        <div class="w-80 bg-white shadow-lg overflow-y-auto">
            <div class="p-4 border-b">
                <h2 class="text-lg font-semibold">{{ $course->title }}</h2>
                <div class="mt-2">
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                    </div>
                    <span class="text-sm text-gray-600">{{ $progress }}% Complete</span>
                </div>
            </div>
            
            <nav class="p-4">
                @foreach($course->lessons as $courseLesson)
                    <a href="{{ route('courses.lessons.show', [$course, $courseLesson]) }}"
                       class="block mb-2 p-3 rounded-lg {{ $lesson->id === $courseLesson->id ? 'bg-blue-50 border-l-4 border-blue-600' : 'hover:bg-gray-50' }}">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-sm font-medium {{ $lesson->id === $courseLesson->id ? 'text-blue-600' : 'text-gray-900' }}">
                                    {{ $courseLesson->title }}
                                </span>
                                <p class="text-xs text-gray-500">{{ $courseLesson->duration }} minutes</p>
                            </div>
                            @if($courseLesson->completedByUser(auth()->user()))
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @endif
                        </div>
                    </a>
                @endforeach
            </nav>
        </div>

        <!-- Main content area -->
        <div class="flex-1 overflow-y-auto">
            <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $lesson->title }}</h1>
                </div>

                <!-- Video Player -->
                @if($lesson->video_url)
                    <div class="aspect-w-16 aspect-h-9 mb-8">
                        <iframe src="{{ $lesson->video_url }}" 
                                class="w-full h-full rounded-lg shadow-lg"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                                id="lessonVideo"></iframe>
                    </div>
                @endif

                <!-- Lesson Content -->
                <div class="prose max-w-none">
                    {!! $lesson->content !!}
                </div>

                <!-- Navigation and Progress -->
                <div class="mt-8 border-t pt-8">
                    <div class="flex items-center justify-between">
                        @if($previousLesson)
                            <a href="{{ route('courses.lessons.show', [$course, $previousLesson]) }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Previous Lesson
                            </a>
                        @endif

                        <form action="{{ route('courses.lessons.complete', [$course, $lesson]) }}" 
                              method="POST" 
                              class="inline-block">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white {{ $lesson->completedByUser(auth()->user()) ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700' }}">
                                @if($lesson->completedByUser(auth()->user()))
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Completed
                                @else
                                    Mark as Complete
                                @endif
                            </button>
                        </form>

                        @if($nextLesson)
                            <a href="{{ route('courses.lessons.show', [$course, $nextLesson]) }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Next Lesson
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Track video progress
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('lessonVideo');
            if (video) {
                let lastUpdateTime = 0;
                const updateInterval = 30; // Update every 30 seconds

                video.addEventListener('timeupdate', function() {
                    const currentTime = Math.floor(video.currentTime);
                    if (currentTime - lastUpdateTime >= updateInterval) {
                        updateProgress(currentTime);
                        lastUpdateTime = currentTime;
                    }
                });
            }
        });

        function updateProgress(watchTime) {
            fetch(`{{ route('courses.lessons.progress', [$course, $lesson]) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ watch_time: watchTime })
            });
        }
    </script>
    @endpush
</x-app-layout> 