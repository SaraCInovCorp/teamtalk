@props([
    'checked' => false,
    'id' => null,
    'name' => null,
    'value' => null,
    'disabled' => false,
])

@if(trim($slot) === '')
    {{-- Apenas o checkbox, sem label visível --}}
    <input 
        type="checkbox" 
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $value }}"
        {{ $checked ? 'checked' : '' }}
        @disabled($disabled)
        {!! $attributes->merge(['class' => 'checkbox checkbox-xs sm:checkbox-sm md:checkbox-md lg:checkbox-lg xl:checkbox-xl']) !!}
    />
@else
    {{-- Checkbox com label embutido inline --}}
    <label class="inline-flex items-center space-x-2 cursor-pointer">
        <input 
            type="checkbox" 
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ $value }}"
            {{ $checked ? 'checked' : '' }}
            @disabled($disabled)
            {!! $attributes->merge(['class' => 'checkbox checkbox-xs sm:checkbox-sm md:checkbox-md lg:checkbox-lg xl:checkbox-xl']) !!}
        />
        <span>{{ $slot }}</span>
    </label>
@endif
