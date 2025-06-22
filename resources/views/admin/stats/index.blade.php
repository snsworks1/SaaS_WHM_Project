@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6">

    <h2 class="text-2xl font-bold mb-4">📊 월간 매출 통계 및 현황</h2>

    <!-- 상단 탭 -->
    <div class="flex flex-wrap gap-4 mb-6">
        <a href="?tab=subscribers" class="px-4 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">서버 가입자 리스트</a>
        <a href="?tab=renewals" class="px-4 py-2 bg-green-100 text-green-700 rounded hover:bg-green-200">이번달 연장 서버 리스트</a>
        <a href="?tab=cancellations" class="px-4 py-2 bg-red-100 text-red-700 rounded hover:bg-red-200">해지 리스트</a>
        <a href="?tab=longterm" class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">장기결제 리스트</a>
    </div>

    <!-- 카드 -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-admin.stats.card icon="fas fa-plus-circle" color="blue" label="신규 결제 수" :value="$newCountsTotal" />
<x-admin.stats.card icon="fas fa-redo-alt" color="green" label="연장 결제 수" :value="$extendCountsTotal" />
<x-admin.stats.card icon="fas fa-times-circle" color="red" label="해지/환불 수" :value="$cancelCountsTotal" />
<x-admin.stats.card icon="fas fa-coins" color="yellow" label="총 매출" :value="$totalSalesSum.'원'" />

    </div>

    <!-- 차트 -->
    <div class="bg-white dark:bg-gray-800 mt-10 p-6 rounded-lg shadow">
        <canvas id="monthlyChart" class="w-full h-72"></canvas>
    </div>

    <!-- 선택된 탭의 리스트 출력 -->
    @if(request()->tab === 'subscribers')
        @include('admin.stats.partials.subscribers')
    @elseif(request()->tab === 'renewals')
        @include('admin.stats.partials.renewals')
    @elseif(request()->tab === 'cancellations')
        @include('admin.stats.partials.cancellations')
    @elseif(request()->tab === 'longterm')
        @include('admin.stats.partials.longterm')
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyLabels) !!},
            datasets: [{
                label: '월별 매출 (원)',
                data: {!! json_encode($monthlySales) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    ticks: {
                        callback: value => value.toLocaleString() + '원'
                    }
                }
            }
        }
    });
});
</script>
@endsection
