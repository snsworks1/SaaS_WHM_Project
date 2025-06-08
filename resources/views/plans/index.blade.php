<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('플랜 선택') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('plans.select') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($plans as $plan)
                        <label class="p-4 border rounded cursor-pointer">
                            <input type="radio" name="plan_id" value="{{ $plan->id }}" class="mr-2" required>
                            {{ $plan->name }} - {{ number_format($plan->price) }}원 ({{ $plan->disk_size }}GB)
                        </label>
                    @endforeach
                </div>

                <div>
                    <label>WHM ID (도메인으로 사용됩니다)</label>
                    <input type="text" name="whm_username" class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label>WHM 비밀번호</label>
                    <input type="password" name="whm_password" class="w-full border rounded p-2" required>
                </div>

                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">
                    계정 생성
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-100 text-green-800 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="p-4 bg-red-100 text-red-800 rounded mb-4">
        {{ $errors->first() }}
    </div>
@endif

</x-app-layout>
