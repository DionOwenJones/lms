<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Employee Header -->
            <div class="mb-6 flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">{{ $employee->name }}</h2>
                    <p class="text-sm text-gray-600">{{ $employee->email }}</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="Livewire.dispatch('openModal', { component: 'business.assign-course', arguments: { employee: {{ $employee->id }} }})"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Assign Course
                    </button>
                    <a href="{{ route('business.employees.index') }}" 
                       class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        Back to Employees
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Employee Stats -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Training Overview</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Enrolled Courses</p>
                            <p class="text-2xl font-semibold">{{ $employee->courses->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Completed Courses</p>
                            <p class="text-2xl font-semibold">{{ $employee->courses->where('pivot.completed', true)->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Average Progress</p>
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-200 rounded-full h-2.5 mr-2">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $employee->averageCourseProgress() }}%"></div>
                                </div>
                                <span class="text-sm font-medium">{{ $employee->averageCourseProgress() }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Progress -->
                <div class="md:col-span-2 bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Course Progress</h3>
                    <div class="space-y-6">
                        @forelse($employee->courses as $course)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $course->title }}</h4>
                                        <p class="text-sm text-gray-500">
                                            Enrolled: {{ $course->pivot->created_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $course->pivot->completed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $course->pivot->completed ? 'Completed' : 'In Progress' }}
                                    </span>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span>Progress</span>
                                        <span>{{ $course->pivot->progress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $course->pivot->progress }}%"></div>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <form action="{{ route('business.employees.courses.remove', [$employee, $course]) }}" 
                                          method="POST" 
                                          class="inline-block"
                                          onsubmit="return confirm('Are you sure you want to remove this course from the employee?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 text-sm font-medium">
                                            Remove Course
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-gray-500">
                                No courses assigned yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 