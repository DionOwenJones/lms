<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                <!-- Logo -->
                <div class="flex justify-center mb-8">
                    <a href="/" class="flex items-center">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    </a>
                </div>

                <!-- Page Content -->
                {{ $slot }}

                <!-- Footer Links -->
                <div class="mt-8 text-center text-sm text-gray-600">
                    @if(Route::has('terms'))
                        <a href="{{ route('terms') }}" class="underline hover:text-gray-900">
                            Terms of Service
                        </a>
                    @endif

                    @if(Route::has('privacy'))
                        <span class="mx-2">·</span>
                        <a href="{{ route('privacy') }}" class="underline hover:text-gray-900">
                            Privacy Policy
                        </a>
                    @endif

                    @if(Route::has('contact'))
                        <span class="mx-2">·</span>
                        <a href="{{ route('contact') }}" class="underline hover:text-gray-900">
                            Contact Us
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success') || session('error'))
            <div x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 4000)"
                 @class([
                    'fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg text-white',
                    'bg-green-500' => session('success'),
                    'bg-red-500' => session('error')
                 ])>
                {{ session('success') ?? session('error') }}
            </div>
        @endif
    </body>
</html>
