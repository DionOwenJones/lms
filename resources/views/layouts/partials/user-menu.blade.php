<div class="space-y-1">
    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
        <x-icons.dashboard class="mr-3 h-5 w-5"/>
        Dashboard
    </x-nav-link>

    <x-nav-link href="{{ route('courses.enrolled') }}" :active="request()->routeIs('courses.enrolled')">
        <x-icons.courses class="mr-3 h-5 w-5"/>
        My Courses
    </x-nav-link>

    <x-nav-link href="{{ route('courses.available') }}" :active="request()->routeIs('courses.available')">
        <x-icons.store class="mr-3 h-5 w-5"/>
        Course Catalog
    </x-nav-link>

    <x-nav-link href="{{ route('certificates') }}" :active="request()->routeIs('certificates')">
        <x-icons.certificate class="mr-3 h-5 w-5"/>
        Certificates
    </x-nav-link>
</div> 