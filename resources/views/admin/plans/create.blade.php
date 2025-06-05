@extends('layouts.admin')

@section('title', '플랜 추가')

@section('content')
<h2 class="text-2xl font-bold mb-4">플랜 추가</h2>

<form method="POST" action="{{ route('admin.plans.store') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block font-semibold">이름</label>
        <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name') }}" required>
    </div>

    <div>
        <label class="block font-semibold">가격 (원)</label>
        <input type="number" name="price" class="w-full border rounded p-2" value="{{ old('price') }}" required>
    </div>

    <div>
        <label class="block font-semibold">디스크 크기 (GB)</label>
        <input type="number" name="disk_size" class="w-full border rounded p-2" value="{{ old('disk_size') }}" required>
    </div>

    <div>
        <label class="block font-semibold">설명</label>
        <textarea name="description" class="w-full border rounded p-2">{{ old('description') }}</textarea>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">저장</button>
    </div>
</form>
@endsection
