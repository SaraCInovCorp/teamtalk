@props([
    'color' => 'currentColor',
    'size' => 'w-5 h-5',
    'src' => null,
])

@if ($src)
    <img 
        src="{{ $src }}" 
        alt="icon" 
        {!! $attributes->merge(['class' => $size]) !!} 
        style="color: {{ $color }}" 
    />
@elseif (trim($slot))
    <span 
        {!! $attributes->merge(['class' => $size . ' flex items-center']) !!} 
        style="color: {{ $color }}"
    >
        {!! $slot !!}
    </span>
@endif
