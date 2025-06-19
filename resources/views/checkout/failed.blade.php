<!-- resources/views/checkout/failed.blade.php -->
<x-app-layout>
    <div class="max-w-xl mx-auto mt-24 bg-white p-6 rounded-lg shadow-md text-center">
        <h2 class="text-2xl font-bold text-red-600 mb-4">🚫 결제 실패</h2>
        <p class="mb-4 text-gray-700">{{ $errorMessage ?? '문제가 발생했습니다.' }}</p>
        <a href="{{ route('dashboard') }}" class="inline-block bg-gray-700 text-white px-6 py-2 rounded hover:bg-gray-800">
            메인으로 돌아가기
        </a>
    </div>
</x-app-layout>
