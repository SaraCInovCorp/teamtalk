@props([
    'as' => 'button',
    'href' => null,
    'type' => 'button',
])

@if ($as === 'a' && $href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'btn btn-wide bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 hover:bg-gray-500 dark:hover:bg-white transition ease-in-out duration-300']) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => 'btn btn-wide bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 hover:bg-gray-500 dark:hover:bg-white transition ease-in-out duration-300']) }}>
        {{ $slot }}
    </button>
@endif
