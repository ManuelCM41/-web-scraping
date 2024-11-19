@props(['url' => '#0', 'counter' => 1, 'label' => 'Label', 'color' => 'gray'])

@php

    $color = [
        'amber' => 'to-amber-800 from-amber-500',
        'blue' => 'to-blue-800 from-blue-500',
        'cyan' => 'to-cyan-800 from-cyan-500',
        'gray' => 'to-gray-800 from-gray-500',
        'green' => 'to-green-800 from-green-500',
        'indigo' => 'to-indigo-800 from-indigo-500',
        'orange' => 'to-orange-800 from-orange-500',
        'purple' => 'to-purple-800 from-purple-500',
        'pink' => 'to-pink-800 from-pink-500',
        'red' => 'to-red-800 from-red-500',
        'teal' => 'to-teal-800 from-teal-500',
        'yellow' => 'to-yellow-800 from-yellow-500',
        'zinc' => 'to-zinc-800 from-zinc-500',
    ][$color ?? 'zinc'];
@endphp

<div class="flex justify-between">
    <div>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $label }}</h2>
        <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase mb-1">un total de</div>
        <div class="text-3xl font-bold text-gray-800 dark:text-gray-100 mr-2">
            @if ($counter <= 2)
                {{ $counter }}
            @else
                <x-counter-animation>{{ $counter }}</x-counter-animation>
            @endif
        </div>
    </div>
    <div class="flex flex-col justify-between items-end">
        <div class="text-white p-4 bg-gradient-to-bl {{ $color }} rounded-lg h-max">
            <i class="fa-solid fa-users fa-lg fa-fw"></i>
        </div>
        <a href="{{ $url }}"
            class="flex items-center gap-2 hover:text-gray-900 hover:font-semibold duration-200">
            <span class="text-sm">ver</span>
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</div>
