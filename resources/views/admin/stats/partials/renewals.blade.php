@extends('layouts.admin')

@section('content')
@if (request()->get('tab') === 'renewals')
<div class="bg-white rounded shadow p-6">
    <h2 class="text-lg font-semibold mb-4">📅 이번달 연장 결제 리스트</h2>

    <table class="w-full table-auto border-collapse text-sm">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="p-2 border-b">유저명</th>
                <th class="p-2 border-b">서비스 도메인</th>
                <th class="p-2 border-b">플랜</th>
                <th class="p-2 border-b">개월수</th>
                <th class="p-2 border-b">금액</th>
                <th class="p-2 border-b">연장일</th>
                <th class="p-2 border-b">만료일</th>
                <th class="p-2 border-b">남은 일수</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($extendPayments as $payment)
                @php
                    $extension = $payment->extension;
                    $service = $extension?->service;
                    $plan = $service?->plan;

                    $paidAt = \Carbon\Carbon::parse($extension?->paid_at);
                    $period = (int) $extension?->period;
                    $endAt = $paidAt->copy()->addMonths($period);
                    $daysLeft = now()->diffInDays($endAt, false);
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="p-2 border-b">{{ $payment->user->name ?? '-' }}</td>
                    <td class="p-2 border-b">
    {{ $service?->whm_domain ?? '-' }}
</td>
                    <td class="p-2 border-b">{{ $plan?->name ?? '-' }}</td>
                    <td class="p-2 border-b">{{ $period }}개월</td>
                    <td class="p-2 border-b">{{ number_format($payment->amount) }}원</td>
                    <td class="p-2 border-b">{{ $paidAt->format('Y-m-d') }}</td>
                    <td class="p-2 border-b">{{ $endAt->format('Y-m-d') }}</td>
                    <td class="p-2 border-b">
                        @if ($daysLeft > 0)
                        @php
    $daysLeft = (int) now()->diffInDays($endAt, false);
@endphp
                            D-{{ $daysLeft }}
                        @elseif ($daysLeft === 0)
                        
                            <span class="text-blue-500 font-semibold">D-DAY</span>
                        @else
                            <span class="text-red-500 font-semibold">만료됨</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="p-4 text-center text-gray-500">이번달 연장 결제가 없습니다.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endif
@endsection
