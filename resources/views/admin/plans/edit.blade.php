@extends('layouts.admin')

@section('title', '플랜 수정')

@section('content')
<h2 class="text-2xl font-bold mb-4">플랜 수정</h2>

<form method="POST" action="{{ route('admin.plans.update', $plan->id) }}" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label class="block font-semibold">이름</label>
        <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name', $plan->name) }}" required>
    </div>

    <div>
        <label class="block font-semibold">가격 (원)</label>
        <input type="number" name="price" class="w-full border rounded p-2" value="{{ old('price', $plan->price) }}" required>
    </div>

    <div>
        <label class="block font-semibold">디스크 크기 (GB)</label>
        <input type="number" name="disk_size" class="w-full border rounded p-2" value="{{ old('disk_size', $plan->disk_size) }}" required>
    </div>

    <div>
        <label class="block font-semibold">설명</label>
        <textarea name="description" class="w-full border rounded p-2">{{ old('description', $plan->description) }}</textarea>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">수정</button>
    </div>
</form>
@endsection
