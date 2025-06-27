<x-app-layout>
        @section('title', '공지사항 - Hostyle')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            📢 공지사항
        </h2>
    </x-slot>

    <div class="py-10 flex justify-center">
        <div class="w-full max-w-5xl bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            @if ($notices->count())
                <table class="min-w-full table-auto text-sm text-center">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2">종류</th>
                            <th class="px-4 py-2">중요도</th>
                            <th class="px-4 py-2">제목</th>
                            <th class="px-4 py-2">날짜</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($notices as $notice)
                            <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-2">{{ $notice->category }}</td>
                                <td class="px-4 py-2">
                                    @if ($notice->importance === '높음')
                                        <span class="text-red-600 font-semibold">{{ $notice->importance }}</span>
                                    @elseif ($notice->importance === '보통')
                                        <span class="text-yellow-500">{{ $notice->importance }}</span>
                                    @else
                                        <span class="text-gray-500">{{ $notice->importance }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('notices.show', $notice->id) }}" class="text-blue-600 hover:underline">
                                        {{ $notice->title }}
                                    </a>
                                </td>
                                <td class="px-4 py-2">{{ $notice->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500">등록된 공지사항이 없습니다.</p>
            @endif
        </div>
    </div>
</x-app-layout>