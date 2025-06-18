@extends('layouts.admin')



@section('content')
<div class="max-w-4xl mx-auto py-10 px-6 bg-white dark:bg-gray-900 rounded-xl shadow-md">
    {{-- 제목 & 메타 --}}
    <div class="border-b pb-4 mb-6 space-y-2">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
            📰 {{ $notice->title }}
        </h1>
        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
            📅 {{ $notice->created_at->format('Y년 m월 d일 H:i') }}
            <span>📂 <span class="font-medium">{{ $notice->category }}</span></span>
            <span>📌 중요도: <span class="font-medium">{{ $notice->importance }}</span></span>
            @if($notice->is_pinned)
                <span class="text-red-600 font-semibold ml-2">🔝 고정 공지</span>
            @endif
        </div>
    </div>

    {{-- 본문 --}}
    <div class="prose dark:prose-invert max-w-none text-[17px] leading-relaxed">
        @php
            $content = json_decode($notice->content, true);
        @endphp

        @if($content && isset($content['blocks']))
            @foreach ($content['blocks'] as $block)
                @switch($block['type'])
                    @case('header')
                        <h{{ $block['data']['level'] }}>{!! $block['data']['text'] !!}</h{{ $block['data']['level'] }}>
                        @break
                    @case('paragraph')
                        <p>{!! $block['data']['text'] !!}</p>
                        @break
                    @case('list')
                        @if($block['data']['style'] === 'ordered')
                            <ol>
                                @foreach ($block['data']['items'] as $item)
                                    <li>{!! $item !!}</li>
                                @endforeach
                            </ol>
                        @else
                            <ul>
                                @foreach ($block['data']['items'] as $item)
                                    <li>{!! $item !!}</li>
                                @endforeach
                            </ul>
                        @endif
                        @break
                    @case('image')
                        <figure>
                            <img src="{{ $block['data']['file']['url'] }}" alt="이미지" class="rounded-lg shadow-md mx-auto">
                            @if(!empty($block['data']['caption']))
                                <figcaption class="text-center text-sm text-gray-500 mt-2">{{ $block['data']['caption'] }}</figcaption>
                            @endif
                        </figure>
                        @break
                    @default
                        <p class="text-sm text-gray-400 italic">[지원하지 않는 블록: {{ $block['type'] }}]</p>
                @endswitch
            @endforeach
        @else
            <p class="text-gray-500 italic">내용이 없습니다.</p>
        @endif
    </div>

    {{-- 목록으로 --}}
    <div class="mt-8 text-right">
        <a href="{{ route('admin.notices.index') }}"
           class="inline-block text-blue-600 hover:text-blue-800 hover:underline transition">
            ← 목록으로 돌아가기
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
    .prose img {
        @apply block mx-auto rounded-md shadow max-w-full h-auto;
    }

    .prose ul, .prose ol {
        @apply list-inside;
    }

    .prose figure {
        @apply my-6;
    }

    .prose figcaption {
        @apply text-center mt-2 text-sm text-gray-500;
    }

    .prose h1, .prose h2, .prose h3 {
        @apply font-bold text-gray-800 dark:text-white;
    }

    .prose p {
        @apply leading-relaxed;
    }
</style>
@endpush
