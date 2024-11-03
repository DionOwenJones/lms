<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-stats-card 
                    title="Total Employees"
                    :value="$employeeCount"
                    color="blue"
                    :icon="$employeeIcon"
                />
                
                <x-stats-card 
                    title="Active Courses"
                    :value="$activeCourseCount"
                    color="green"
                    :icon="$courseIcon"
                />
                
                <x-stats-card 
                    title="Completion Rate"
                    :value="$completionRate . '%'"
                    color="yellow"
                    :icon="$completionIcon"
                />
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Enrollments -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Enrollments</h3>
                        <div class="space-y-4">
                            @foreach($recentEnrollments as $enrollment)
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $enrollment->user->name }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 