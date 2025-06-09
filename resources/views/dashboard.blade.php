<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">내 서비스 목록</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($services as $service)
                <div class="bg-white shadow rounded-2xl p-6 border">
                    <h3 class="font-bold text-lg mb-2">{{ $service->plan->name }}</h3>
                    <p class="text-gray-700 mb-1"><strong>도메인:</strong> {{ $service->whm_domain }}</p>
                    <p class="text-gray-700 mb-1"><strong>WHM 계정:</strong> {{ $service->whm_username }}</p>
                    <p class="text-gray-500 text-sm">생성: {{ $service->created_at->format('Y-m-d H:i') }}</p>
                </div>
            @empty
                <div class="col-span-3 text-center text-gray-600">
                    생성된 서비스가 없습니다.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
