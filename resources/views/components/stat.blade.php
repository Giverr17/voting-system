@props(['title', 'value', 'color'])

<div class="bg-white shadow rounded-lg p-4">
    <p class="text-sm text-gray-500">{{ $title }}</p>
    <p class="text-3xl font-bold text-{{ $color }}">
        {{ $value }}
    </p>
</div>
