@props(['icon', 'label', 'value', 'color' => 'text-blue-500'])

<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow flex items-center space-x-4">
    <div class="text-3xl {{ $color }}">
        <i class="{{ $icon }}"></i>
    </div>
    <div>
        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</div>
        <div class="text-xl font-bold">{{ $value }}</div>
    </div>
</div>
