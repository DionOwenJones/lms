<div class="space-y-1">
    <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
        <x-icons.dashboard class="mr-3 h-5 w-5"/>
        Dashboard
    </x-nav-link>

    <x-nav-link href="{{ route('admin.courses.index') }}" :active="request()->routeIs('admin.courses.*')">
        <x-icons.courses class="mr-3 h-5 w-5"/>
        Courses
    </x-nav-link>

    <x-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
        <x-icons.users class="mr-3 h-5 w-5"/>
        Users
    </x-nav-link>

    <x-nav-link href="{{ route('admin.businesses.index') }}" :active="request()->routeIs('admin.businesses.*')">
        <x-icons.business class="mr-3 h-5 w-5"/>
        Businesses
    </x-nav-link>

    <x-nav-link href="{{ route('admin.reports') }}" :active="request()->routeIs('admin.reports')">
        <x-icons.reports class="mr-3 h-5 w-5"/>
        Reports
    </x-nav-link>
</div> 