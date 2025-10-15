@props(['disabled' => false])
<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'textarea textarea-xs sm:textarea-sm md:textarea-md lg:textarea-lg xl:textarea-xl']) !!}>
    {{ $slot }}
</textarea>
