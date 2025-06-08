<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">플랜 선택</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto">
            <form method="POST" action="{{ route('plans.select') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($plans as $plan)
                        <label class="p-4 border rounded cursor-pointer">
                            <input type="radio" name="plan_id" value="{{ $plan->id }}" required>
                            {{ $plan->name }} - {{ number_format($plan->price) }}원 ({{ $plan->disk_size }}GB)
                        </label>
                    @endforeach
                </div>

                <div>
                    <label>WHM 아이디 (도메인 앞부분)</label>
                    <input type="text" name="whm_username" class="w-full border rounded p-2" required placeholder="영문 아이디 입력">
                    <small>입력한 값 → {아이디}.cflow.dev 생성</small>
                </div>

                <div>
                    <label>비밀번호</label>
                    <input type="password" name="whm_password" class="w-full border rounded p-2" required>
                </div>

                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">
                    계정 생성
                </button>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    document.querySelector('input[name="whm_username"]').addEventListener('blur', function() {
    let username = this.value;
    fetch('/plans/check-username', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ whm_username: username })
    }).then(res => res.json()).then(data => {
        if (!data.available) {
            alert('이미 사용중인 WHM ID입니다.');
            this.value = '';
        }
    });
});

    </script>