<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-4">{{ $lesson->title }}</h1>
                    
                    <!-- Video Player -->
                    @if($lesson->video_url)
                    <div class="aspect-w-16 aspect-h-9 mb-6">
                        <iframe 
                            src="{{ $lesson->video_url }}" 
                            class="w-full"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                    </div>
                    @endif

                    <!-- Lesson Content -->
                    <div class="prose max-w-none">
                        {!! $lesson->content !!}
                    </div>

                    <!-- Progress Tracking -->
                    <div class="mt-6 flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="lessonComplete" 
                                   class="rounded border-gray-300"
                                   {{ $progress->completed ? 'checked' : '' }}
                                   wire:click="toggleComplete">
                            <label for="lessonComplete" class="ml-2">Mark as Complete</label>
                        </div>
                        
                        <div class="flex space-x-4">
                            @if($lesson->course->lessons()->where('order', '<', $lesson->order)->exists())
                                <a href="#" class="text-blue-600 hover:text-blue-800">Previous Lesson</a>
                            @endif
                            
                            @if($lesson->course->lessons()->where('order', '>', $lesson->order)->exists())
                                <a href="#" class="text-blue-600 hover:text-blue-800">Next Lesson</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Add your video progress tracking JavaScript here
    </script>
    @endpush
</x-app-layout> 