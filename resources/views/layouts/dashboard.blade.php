<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ConstructionTraining') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-white transition-transform duration-300">
            <div class="flex h-16 items-center justify-start px-4">
                <span class="text-xl font-bold">ConstructionTraining</span>
            </div>
            
            <nav class="mt-5 px-2">
                @if(auth()->user()->isAdmin())
                    @include('layouts.partials.admin-menu')
                @elseif(auth()->user()->isBusiness())
                    @include('layouts.partials.business-menu')
                @else
                    @include('layouts.partials.user-menu')
                @endif
            </nav>
        </div>

        <!-- Main Content -->
        <div class="pl-64">
            <!-- Top Navigation -->
            <div class="bg-white h-16 fixed right-0 left-64 top-0 z-10 border-b">
                <div class="flex items-center justify-between h-full px-6">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">
                            @yield('header')
                        </h1>
                    </div>
                    <div class="flex items-center">
                        <x-dropdown>
                            <x-slot name="trigger">
                                <button class="flex items-center text-gray-700 hover:text-gray-900">
                                    {{ Auth::user()->name }}
                                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link href="{{ route('profile.edit') }}">
                                    Profile
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        Logout
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="pt-16">
                <div class="py-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
    @livewireScripts
</body>
</html> 