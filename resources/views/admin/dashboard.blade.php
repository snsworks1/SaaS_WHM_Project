@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto p-6">
        <h2 class="text-2xl font-bold mb-6">📊 실시간 관리자 대시보드</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- 오늘 신규 유저 -->
            <div class="bg-white p-5 rounded-lg shadow flex items-center space-x-4">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 10-6 0 3 3 0 006 0z"/>
                </svg>
                <div>
                    <p class="text-sm text-gray-500">오늘 신규 유저</p>
                    <p class="text-xl font-bold text-blue-600">{{ number_format($todayNewUsers) }}</p>
                </div>
            </div>

            <!-- 오늘 신규 서버 -->
            <div class="bg-white p-5 rounded-lg shadow flex items-center space-x-4">
                <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 10h18M3 6h18M4 14h16M4 18h16"/>
                </svg>
                <div>
                    <p class="text-sm text-gray-500">오늘 신규 서버</p>
                    <p class="text-xl font-bold text-indigo-600">{{ number_format($todayNewServers) }}</p>
                </div>
            </div>

            <!-- 오늘 매출 -->
            <div class="bg-white p-5 rounded-lg shadow flex items-center space-x-4">
                <svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 8c-1.5 0-3 .5-4 1.5V6H6v12h2v-3c1 1 2.5 1.5 4 1.5s3-.5 4-1.5V18h2V6h-2v3c-1-1-2.5-1.5-4-1.5z"/>
                </svg>
                <div>
                    <p class="text-sm text-gray-500">오늘 매출</p>
                    <p class="text-xl font-bold text-rose-600">{{ number_format($todaySales) }}원</p>
                </div>
            </div>

            <!-- 전체 유저 수 -->
            <div class="bg-white p-5 rounded-lg shadow flex items-center space-x-4">
                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-6a4 4 0 110-8 4 4 0 010 8zm0 0v1a3 3 0 00-3 3v2h6v-2a3 3 0 00-3-3z"/>
                </svg>
                <div>
                    <p class="text-sm text-gray-500">전체 유저 수</p>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($totalUsers) }}</p>
                </div>
            </div>

            <!-- 전체 서버 수 -->
            <div class="bg-white p-5 rounded-lg shadow flex items-center space-x-4">
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                <div>
                    <p class="text-sm text-gray-500">전체 서버 수</p>
                    <p class="text-xl font-bold text-yellow-600">{{ number_format($totalServers) }}</p>
                </div>
            </div>

            <!-- 전체 WHM 서버 수 -->
            <div class="bg-white p-5 rounded-lg shadow flex items-center space-x-4">
                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 7h18M3 12h18M3 17h18"/>
                </svg>
                <div>
                    <p class="text-sm text-gray-500">WHM 서버 수</p>
                    <p class="text-xl font-bold text-purple-600">{{ number_format($totalWhmServers) }}</p>
                </div>
            </div>
        </div>

        <!-- 📊 차트 영역 -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">📈 월간 변화 추이 (예정)</h3>
            <canvas id="monthlyStatsChart" height="120"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('monthlyStatsChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['1월', '2월', '3월', '4월', '5월', '6월'],
                datasets: [{
                    label: '매출 추이',
                    data: [0, 10000, 30000, 25000, 40000, 50000],
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.3,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
