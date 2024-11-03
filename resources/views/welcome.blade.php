<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Construction Training - Professional Construction Courses</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="bg-white">
            <!-- Header -->
            <header class="fixed w-full bg-white/90 backdrop-blur-sm shadow-sm z-50">
                <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">Construction Training</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Register</a>
                            @endif
                        @endauth
                    </div>
                </nav>
            </header>

            <!-- Hero Section -->
            <div class="relative pt-32 pb-32 flex content-center items-center justify-center min-h-screen">
                <div class="absolute top-0 w-full h-full bg-center bg-cover" style="background-image: url('https://images.unsplash.com/photo-1590274853856-f22d5ee3d228?ixlib=rb-1.2.1&auto=format&fit=crop&w=2850&q=80');">
                    <span class="w-full h-full absolute opacity-50 bg-black"></span>
                </div>
                <div class="container relative mx-auto">
                    <div class="items-center flex flex-wrap">
                        <div class="w-full lg:w-6/12 px-4 ml-auto mr-auto text-center">
                            <div class="text-white">
                                <h1 class="text-5xl font-semibold">Transform Your Construction Career</h1>
                                <p class="mt-4 text-lg">Professional training courses for construction workers and businesses. Enhance your skills and stay compliant with industry standards.</p>
                                <a href="{{ route('courses.index') }}" class="mt-8 inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700">
                                    Explore Courses
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <section class="py-20 bg-white">
                <div class="container mx-auto px-4">
                    <div class="flex flex-wrap">
                        <div class="lg:pt-12 pt-6 w-full md:w-4/12 px-4 text-center">
                            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-8 shadow-lg rounded-lg">
                                <div class="px-4 py-5 flex-auto">
                                    <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-red-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <h6 class="text-xl font-semibold">Individual Training</h6>
                                    <p class="mt-2 mb-4 text-gray-600">Access professional construction courses to enhance your skills and advance your career.</p>
                                </div>
                            </div>
                        </div>

                        <div class="w-full md:w-4/12 px-4 text-center">
                            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-8 shadow-lg rounded-lg">
                                <div class="px-4 py-5 flex-auto">
                                    <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-blue-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <h6 class="text-xl font-semibold">Business Solutions</h6>
                                    <p class="mt-2 mb-4 text-gray-600">Train your workforce with our comprehensive business packages and employee management tools.</p>
                                </div>
                            </div>
                        </div>

                        <div class="lg:pt-12 pt-6 w-full md:w-4/12 px-4 text-center">
                            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-8 shadow-lg rounded-lg">
                                <div class="px-4 py-5 flex-auto">
                                    <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-green-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h6 class="text-xl font-semibold">Certified Courses</h6>
                                    <p class="mt-2 mb-4 text-gray-600">Industry-recognized certifications and compliance training for construction professionals.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </body>
</html>
