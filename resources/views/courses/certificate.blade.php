<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Certificate Header -->
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                    <div class="relative p-8 text-center text-white">
                        <h1 class="text-4xl font-bold mb-2">Certificate of Completion</h1>
                        <p class="text-lg">This is to certify that</p>
                    </div>
                </div>

                <!-- Certificate Content -->
                <div class="p-8 text-center">
                    <h2 class="text-3xl font-serif mb-6">{{ auth()->user()->name }}</h2>
                    <p class="text-lg mb-6">has successfully completed the course</p>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ $course->title }}</h3>

                    <div class="mb-8">
                        <p class="text-gray-600">with a total duration of {{ $course->duration }} minutes</p>
                        <p class="text-gray-600">completed on {{ $completion_date->format('F d, Y') }}</p>
                    </div>

                    <!-- Certificate Details -->
                    <div class="grid grid-cols-2 gap-8 max-w-2xl mx-auto mb-8">
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Certificate ID</p>
                            <p class="font-medium">{{ $certificate_id }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Issued By</p>
                            <p class="font-medium">Construction Training</p>
                        </div>
                    </div>

                    <!-- Signature Section -->
                    <div class="border-t pt-8">
                        <div class="flex justify-center space-x-16">
                            <div class="text-center">
                                <div class="mb-2">
                                    <img src="{{ asset('images/signature.png') }}" 
                                         alt="Instructor Signature" 
                                         class="h-16 mx-auto">
                                </div>
                                <div class="w-40 border-t border-gray-300"></div>
                                <p class="mt-2 text-sm text-gray-600">Course Instructor</p>
                            </div>
                            <div class="text-center">
                                <div class="mb-2">
                                    <img src="{{ asset('images/seal.png') }}" 
                                         alt="Official Seal" 
                                         class="h-16 mx-auto">
                                </div>
                                <div class="w-40 border-t border-gray-300"></div>
                                <p class="mt-2 text-sm text-gray-600">Official Seal</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 flex justify-center space-x-4">
                        <button onclick="window.print()" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print Certificate
                        </button>
                        <a href="{{ route('courses.download-certificate', $course) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .max-w-4xl, .max-w-4xl * {
                visibility: visible;
            }
            .max-w-4xl {
                position: absolute;
                left: 0;
                top: 0;
            }
            .actions {
                display: none;
            }
        }
    </style>
    @endpush
</x-app-layout> 