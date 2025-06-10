@props(['index', 'label', 'step'])

<div class="flex flex-col items-center">
    <div class="w-10 h-10 rounded-full border-4
        {{ $step == $index ? 'border-green-500 bg-green-500 text-white'
            : ($step > $index ? 'border-green-300 bg-green-300 text-white'
            : 'border-gray-300 bg-gray-100 text-gray-600') }}
        flex items-center justify-center text-sm font-bold shadow">
        {{ $index }}
    </div>
    <div class="mt-2 text-xs font-medium text-gray-800 text-center">{{ $label }}</div>
</div>
