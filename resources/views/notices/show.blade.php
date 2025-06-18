<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            📌 {{ $notice->title }}
        </h2>
    </x-slot>

    <div class="py-10 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow p-6 rounded-lg">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-300">
                📂 종류: <strong>{{ $notice->category }}</strong> |
                📌 중요도: 
                @if ($notice->importance === '높음')
                    <span class="text-red-600 font-semibold">{{ $notice->importance }}</span>
                @elseif ($notice->importance === '보통')
                    <span class="text-yellow-500">{{ $notice->importance }}</span>
                @else
                    <span class="text-gray-500">{{ $notice->importance }}</span>
                @endif
                | 🕒 작성일: {{ $notice->created_at->format('Y-m-d H:i') }}
            </div>

            <div class="prose max-w-none dark:prose-invert">
                @php
                    $blocks = json_decode($notice->content)->blocks ?? [];
                @endphp

                @foreach ($blocks as $block)
                    @if ($block->type === 'header')
                        <h{{ $block->data->level }}>{{ $block->data->text }}</h{{ $block->data->level }}>
                    @elseif ($block->type === 'paragraph')
                        <p>{{ $block->data->text }}</p>
                    @elseif ($block->type === 'list')
                        @if ($block->data->style === 'ordered')
                            <ol>
                                @foreach ($block->data->items as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ol>
                        @else
                            <ul>
                                @foreach ($block->data->items as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @endif
                    @elseif ($block->type === 'image')
                        <img src="{{ $block->data->file->url }}" alt="{{ $block->data->caption ?? '' }}" class="my-4 max-w-full">
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
