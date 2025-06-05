<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('대시보드') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (auth()->user()->plan)
                    <h3 class="text-xl font-bold mb-4">이용 중인 서비스</h3>

                    <div class="bg-gray-100 p-4 rounded shadow">
                        <p><strong>플랜명:</strong> {{ auth()->user()->plan->name }}</p>
                        <p><strong>가격:</strong> {{ number_format(auth()->user()->plan->price) }}원</p>
                        <p><strong>디스크 용량:</strong> {{ auth()->user()->plan->disk_size }} GB</p>
                    </div>
                @else
                    <div class="text-center">
                        <p>이용중인 서비스가 없습니다. <a href="{{ route('plans.index') }}" class="text-blue-500 underline">플랜 선택하기</a></p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
