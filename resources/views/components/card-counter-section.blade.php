@props(['url' => '#0', 'counter' => 1, 'label' => 'Label', 'color' => 'gray'])


<div class="flex justify-between">
    <div>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $label }}</h2>
        <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase mb-1">un total de</div>
        <div class="text-3xl font-bold text-gray-800 dark:text-gray-100 mr-2">
            <x-counter-animation>{{ $counter }}</x-counter-animation>
        </div>
    </div>
    <div class="flex flex-col justify-between items-end">
        <div class="text-white p-4 bg-gradient-to-bl to-{{ $color }}-800 from-{{ $color }}-500 rounded-lg h-max">
            <i class="fa-solid fa-users fa-lg fa-fw"></i>
        </div>
        <a href="{{ $url }}"
            class="flex items-center gap-2 hover:text-gray-900 hover:font-semibold duration-200">
            <span class="text-sm">ver</span>
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</div>
