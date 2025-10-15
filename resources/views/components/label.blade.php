@props(['value'])

<label {{ $attributes->merge(['class' => 'floating-label ']) }}>
    {{ $slot }}
    <span class="font-bold text-gray-700">{{ $value }}</span>
</label>