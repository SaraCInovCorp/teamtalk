@props([
    'as' => 'button',
    'href' => null,
    'type' => 'button',
])

@if ($as === 'a' && $href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'btn btn-wide bg-teamtalk-gray text-white hover:bg-teamtalk-gray/50 transition ease-in-out duration-300']) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => 'btn btn-wide bg-teamtalk-gray text-white hover:bg-teamtalk-gray/50 transition ease-in-out duration-300']) }}>
        {{ $slot }}
    </button>
@endif
