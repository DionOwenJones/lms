<div class="space-y-1">
    <x-nav-link href="{{ route('business.dashboard') }}" :active="request()->routeIs('business.dashboard')">
        <x-icons.dashboard class="mr-3 h-5 w-5"/>
        Dashboard
    </x-nav-link>

    <x-nav-link href="{{ route('business.employees.index') }}" :active="request()->routeIs('business.employees.*')">
        <x-icons.users class="mr-3 h-5 w-5"/>
        Employees
    </x-nav-link>

    <x-nav-link href="{{ route('business.courses.purchased') }}" :active="request()->routeIs('business.courses.*')">
        <x-icons.courses class="mr-3 h-5 w-5"/>
        Courses
    </x-nav-link>

    <x-nav-link href="{{ route('business.reports') }}" :active="request()->routeIs('business.reports')">
        <x-icons.reports class="mr-3 h-5 w-5"/>
        Reports
    </x-nav-link>
</div> 