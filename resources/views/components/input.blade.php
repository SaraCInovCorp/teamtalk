@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'input input-xs sm:input-sm md:input-md lg:input-lg xl:input-xl']) !!}>
