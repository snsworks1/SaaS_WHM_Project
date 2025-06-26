@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-4">
    <h2 class="text-xl font-bold mb-4">❌ 해지/환불 내역</h2>
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full table-auto text-sm">
           <thead class="bg-gray-100 dark:bg-gray-700">
    <tr>
        <th class="px-4 py-2 text-left">유저명</th>
        <th class="px-4 py-2 text-left">플랜</th>
        <th class="px-4 py-2 text-left">결제금액</th>
         <th class="px-4 py-2 text-left">환불금액</th>
          <th class="px-4 py-2 text-left">공제금액</th>
        <th class="px-4 py-2 text-left">환불일시</th>
        <th class="px-4 py-2 text-left">환불 사유</th>
        <th class="px-4 py-2 text-left">사용일자</th>
    </tr>
</thead>
<tbody>
@foreach ($cancelPayments as $payment)
    <tr class="border-b dark:border-gray-700">
        <td class="px-4 py-2">{{ $payment->user->name }}</td>
        <td class="px-4 py-2">{{ optional($payment->plan)->name ?? '-' }}</td>
        <td class="px-4 py-2">{{ number_format($payment->amount) }}원</td>
                <td class="px-4 py-2">{{ number_format($payment->refunded_amount ?? 0) }}원</td>
        <td class="px-4 py-2">{{ number_format(($payment->amount ?? 0) - ($payment->refunded_amount ?? 0)) }}원</td>
        <td class="px-4 py-2">{{ $payment->updated_at->format('Y-m-d H:i') }}</td>
        <td class="px-4 py-2">{{ $payment->refund_reason ?? '-' }}</td>
        <td class="px-4 py-2">
            @if ($payment->start_at)
{{ (int) \Carbon\Carbon::parse($payment->start_at)->diffInDays($payment->updated_at) }}일 사용됨
            @else
                -
            @endif
        </td>
    </tr>
@endforeach
</tbody>
        </table>
    </div>
</div>
@endsection
