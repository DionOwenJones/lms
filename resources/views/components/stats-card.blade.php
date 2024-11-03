@props(['title', 'value', 'icon', 'color' => 'blue'])

<div class="bg-white overflow-hidden shadow-sm rounded-lg">
    <div class="p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 rounded-md p-3 {{ "bg-{$color}-100" }}">
                {{ $icon }}
            </div>
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        {{ $title }}
                    </dt>
                    <dd class="text-2xl font-semibold text-gray-900">
                        {{ $value }}
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div> 