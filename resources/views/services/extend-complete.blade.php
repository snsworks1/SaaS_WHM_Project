<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">서비스 연장 완료</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-8">
            <div class="text-center">
                <div class="text-green-500 text-5xl mb-4">✅</div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">서비스 연장이 완료되었습니다</h1>
                <p class="text-gray-600 mb-6">
                    <strong>{{ $service->domain }}</strong> 서비스의 만료일이 연장되었습니다.
                </p>

                <div class="mb-6">
                    <p class="text-sm text-gray-500">새 만료일</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $service->expired_at->format('Y년 m월 d일') }}</p>
                </div>

                <a href="{{ route('dashboard') }}"
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow text-sm font-medium transition">
                    대시보드로 이동
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
