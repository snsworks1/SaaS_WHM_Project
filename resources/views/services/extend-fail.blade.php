<x-app-layout>
    <div class="max-w-xl mx-auto py-12 text-center">
        <h2 class="text-2xl font-bold text-red-600">❌ 결제 실패</h2>
        <p class="mt-4 text-gray-700">서비스 연장 결제가 실패했습니다.<br>다시 시도해 주세요.</p>
        <a href="{{ route('services.settings', $id) }}"
           class="mt-6 inline-block bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">
           돌아가기
        </a>
    </div>
</x-app-layout>
