@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-4">
    <h2 class="text-xl font-bold mb-4">🟢 신규 가입자 리스트</h2>
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full table-auto text-sm">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">이름</th>
                    <th class="px-4 py-2 text-left">이메일</th>
                    <th class="px-4 py-2 text-left">가입일</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($newUsers as $user)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">{{ $user->created_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
