


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">플랜 업그레이드</h2>
    </x-slot>

    <div class="py-12">
    @include('components.upgrade-progress', ['step' => 1])
        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
            
            <h3 class="font-bold text-lg mb-4">현재 플랜: {{ $service->plan->name }}</h3>

            <form method="POST" action="{{ route('services.confirmUpgrade', $service->id) }}">
                @csrf

                <label for="plan_id" class="block mb-2">업그레이드 할 플랜 선택:</label>
                <select name="plan_id" id="plan_id" class="w-full border rounded p-2 mb-4" required>
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }} ({{ $plan->disk_size }}GB, {{ $plan->price }}원)</option>
                    @endforeach
                </select>

                <button type="submit" class="w-full bg-yellow-500 text-white py-2 rounded hover:bg-yellow-600">
                    다음 → 요금 확인
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
