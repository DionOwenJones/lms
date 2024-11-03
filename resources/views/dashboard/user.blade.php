<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Banner -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                    <h2 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h2>
                    <p class="text-blue-100">Continue your learning journey</p>
                </div>
            </div>

            <!-- Progress Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-stats-card 
                    title="Courses in Progress"
                    :value="$inProgressCourses->count()"
                    color="blue"
                >
                    <x-slot:icon>
                        <x-icons.book-open class="w-6 h-6 text-blue-600"/>
                    </x-slot:icon>
                </x-stats-card>

                <x-stats-card 
                    title="Completed Courses"
                    :value="$completedCourses->count()"
                    color="green"
                >
                    <x-slot:icon>
                        <x-icons.check-circle class="w-6 h-6 text-green-600"/>
                    </x-slot:icon>
                </x-stats-card>

                <x-stats-card 
                    title="Certificates Earned"
                    :value="$certificates->count()"
                    color="yellow"
                >
                    <x-slot:icon>
                        <x-icons.certificate class="w-6 h-6 text-yellow-600"/>
                    </x-slot:icon>
                </x-stats-card>
            </div>

            <!-- Course Progress -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- In Progress Courses -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Continue Learning</h3>
                        @forelse($inProgressCourses as $course)
                            <div class="mb-4 last:mb-0">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="text-base font-medium text-gray-900">{{ $course->title }}</h4>
                                        <p class="text-sm text-gray-500">{{ $course->pivot->progress }}% Complete</p>
                                    </div>
                                    <a href="{{ route('courses.learn', $course) }}" 
                                       class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                                        Continue
                                    </a>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" 
                                         style="width: {{ $course->pivot->progress }}%">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-gray-500 mb-4">No courses in progress</p>
                                <a href="{{ route('courses.available') }}" 
                                   class="inline-flex items-center text-blue-600 hover:text-blue-700">
                                    Browse Available Courses
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Certificates -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Certificates</h3>
                        @forelse($certificates->take(5) as $certificate)
                            <div class="flex items-center justify-between py-3 border-b last:border-0">
                                <div>
                                    <h4 class="text-base font-medium text-gray-900">{{ $certificate->course->title }}</h4>
                                    <p class="text-sm text-gray-500">Completed {{ $certificate->created_at->format('M d, Y') }}</p>
                                </div>
                                <a href="{{ route('certificates.download', $certificate) }}" 
                                   class="text-blue-600 hover:text-blue-700">
                                    <x-icons.download class="w-5 h-5"/>
                                </a>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">Complete courses to earn certificates</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 