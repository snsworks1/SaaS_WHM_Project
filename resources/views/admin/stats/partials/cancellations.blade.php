@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-4">
    <h2 class="text-xl font-bold mb-4">❌ 해지/환불 내역</h2>
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full table-auto text-sm">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">유저명</th>
                    <th class="px-4 py-2 text-left">서비스</th>
                    <th class="px-4 py-2 text-left">환불 사유</th>
                    <th class="px-4 py-2 text-left">날짜</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cancelPayments as $payment)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-2">{{ $payment->user->name }}</td>
                        <td class="px-4 py-2">{{ $payment->service->domain }}</td>
                        <td class="px-4 py-2">{{ $payment->refund_reason ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $payment->updated_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
