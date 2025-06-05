@extends('layouts.admin')

@section('title', '회원 수정')

@section('content')
<h2 class="text-2xl font-bold mb-4">회원 정보 수정</h2>

<form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label class="block font-semibold">이름</label>
        <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name', $user->name) }}" required>
    </div>

    <div>
        <label class="block font-semibold">전화번호</label>
        <input type="text" name="phone" class="w-full border rounded p-2" value="{{ old('phone', $user->phone) }}">
    </div>

    <div>
        <label class="block font-semibold">플랜 선택</label>
        <select name="plan_id" class="w-full border rounded p-2">
            <option value="">선택안함</option>
            @foreach($plans as $plan)
                <option value="{{ $plan->id }}" @if(old('plan_id', $user->plan_id) == $plan->id) selected @endif>
                    {{ $plan->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">저장</button>
    </div>
</form>
@endsection
