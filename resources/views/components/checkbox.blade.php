@props([
    'checked' => false,
    'id' => null,
    'name' => null,
    'value' => null,
    'disabled' => false,
])

<input 
    type="checkbox" 
    id="{{ $id }}"
    name="{{ $name }}"
    value="{{ $value }}"
    {{ $checked ? 'checked' : '' }}
    @disabled($disabled)
    {!! $attributes->merge(['class' => 'checkbox checkbox-xs sm:checkbox-sm md:checkbox-md lg:checkbox-lg xl:checkbox-xl']) !!}
/>
