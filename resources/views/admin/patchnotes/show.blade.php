@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <h1 class="text-3xl font-bold mb-4">{{ $patchnote->title }}</h1>

    <div class="text-sm text-gray-500 mb-6">
        주요 패치 기능: <strong>{{ $patchnote->summary }}</strong> · 등록일: {{ $patchnote->created_at->format('Y-m-d') }} · 조회수: {{ $patchnote->views }}
    </div>

    <div class="prose max-w-none">
        {!! $patchnote->content !!}
    </div>

    <div class="mt-6">
        <a href="{{ route('patchnotes.index') }}" class="text-blue-600 hover:underline">← 목록으로 돌아가기</a>
    </div>
</div>
@endsection
