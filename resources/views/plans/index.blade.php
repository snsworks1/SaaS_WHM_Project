<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('플랜 선택') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($plans as $plan)
                    <div class="bg-white shadow-md rounded-lg p-6 text-center border border-gray-200 hover:shadow-lg transition duration-300">
                        <h3 class="text-2xl font-bold mb-4">{{ $plan->name }}</h3>
                        <p class="text-lg mb-2">가격: <span class="font-semibold">{{ number_format($plan->price) }}원</span></p>
                        <p class="text-lg mb-4">디스크 용량: {{ $plan->disk_size }} GB</p>

                        <form method="POST" action="{{ route('plans.select') }}">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <button type="submit" class="w-full py-3 bg-indigo-500 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-600 transition duration-300">
                                선택하기
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>



