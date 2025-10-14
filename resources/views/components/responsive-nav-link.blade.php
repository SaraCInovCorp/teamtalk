@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full ps-3 pe-4 py-2 border-b-4 border-yellow-300 text-center text-base font-medium text-white bg-blue-900 focus:outline-none focus:text-yellow-100 focus:border-yellow-500 transition duration-150 ease-in-out flex items-center justify-center'
    : 'block w-full ps-3 pe-4 py-2 border-b-4 border-transparent text-center text-base font-medium text-white hover:text-yellow-100 hover:border-yellow-400 focus:outline-none focus:text-yellow-100 focus:bg-yellow-100 focus:border-yellow-500 transition duration-150 ease-in-out flex items-center justify-center';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
