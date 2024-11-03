@props([
    'name',
    'label' => null,
    'value' => null,
    'type' => 'text',
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'helper' => null,
    'leadingIcon' => null,
    'trailingIcon' => null,
])

<div class="w-full">
    @if($label)
        <label for="{{ $name }}" @class([
            'block text-sm font-medium mb-1',
            'text-gray-900' => !$errors->has($name),
            'text-red-600' => $errors->has($name)
        ])>
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <div class="relative rounded-md shadow-sm">
        @if($leadingIcon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <x-dynamic-component :component="$leadingIcon" class="h-5 w-5 text-gray-400" />
            </div>
        @endif

        <input 
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            @if($required) required @endif
            @if($disabled) disabled @endif
            {{ $attributes->merge([
                'class' => 'block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ' . 
                ($leadingIcon ? 'pl-10 ' : 'pl-4 ') .
                ($trailingIcon ? 'pr-10' : 'pr-4') .
                ($errors->has($name) 
                    ? 'ring-red-300 placeholder-red-300 focus:ring-red-500' 
                    : 'ring-gray-300 placeholder-gray-400 focus:ring-blue-500') .
                ($disabled ? ' bg-gray-50 text-gray-500 cursor-not-allowed' : '')
            ]) }}
        />

        @if($trailingIcon)
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <x-dynamic-component :component="$trailingIcon" class="h-5 w-5 text-gray-400" />
            </div>
        @endif

        @error($name)
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
        @enderror
    </div>

    @if($helper && !$errors->has($name))
        <p class="mt-2 text-sm text-gray-500">{{ $helper }}</p>
    @endif

    @error($name)
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div> 