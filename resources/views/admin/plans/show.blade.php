@extends('layouts.admin')

@section('title', '플랜 상세')

@section('content')
<h2 class="text-2xl font-bold mb-4">플랜 상세</h2>

<div class="p-4 bg-white rounded shadow space-y-4">
    <div><strong>이름:</strong> {{ $plan->name }}</div>
    <div><strong>가격:</strong> {{ $plan->price }} 원</div>
    <div><strong>디스크 용량:</strong> {{ $plan->disk_size }} GB</div>
    <div><strong>설명:</strong> {{ $plan->description }}</div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.plans.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">목록으로</a>
</div>
@endsection
