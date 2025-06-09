@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('css/admin-table.css') }}">

@section('content')
<div class="admin-table-container">
    <h2 class="admin-title">전체 서비스 모니터링</h2>

    @if (session('success'))
        <div class="admin-alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>서버명</th>
                    <th>WHM IP</th>
                    <th>WHM 계정</th>
                    <th>고객 이메일</th>
                    <th>플랜</th>
                    <th>상태</th>
                    <th>만료일</th>
                    <th>D-Day</th>
                    <th>관리</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($services as $service)
                <tr>
                    <td>{{ $service->whmServer->name }}</td>
                    <td>{{ $service->whmServer->ip_address ?? '-' }}</td>
                    <td>{{ $service->whm_username }}</td>
                    <td>{{ $service->user->email }}</td>
                    <td>{{ $service->plan->name }}</td>
                    <td>
                        @php
                            $statusClass = match($service->status) {
                                'active' => 'admin-badge-green',
                                'suspended' => 'admin-badge-yellow',
                                'deleted' => 'admin-badge-red',
                                default => 'admin-badge-gray',
                            };
                            $statusText = match($service->status) {
                                'active' => '사용중',
                                'suspended' => '일시정지',
                                'deleted' => '삭제됨',
                                default => '알수없음',
                            };
                        @endphp
                        <span class="admin-badge {{ $statusClass }}">{{ $statusText }}</span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($service->expired_at)->format('Y-m-d') }}</td>
                    <td>
                        @php 
                            $daysLeft = intval($service->days_left);
                        @endphp
                        @if($daysLeft >= 0)
                            <span class="admin-dday-green">D-{{ $daysLeft }}</span>
                        @else
                            <span class="admin-dday-red">만료 {{ abs($daysLeft) }}일 지남</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.services.edit', $service->id) }}" class="admin-btn-edit">수정</a>

                        <form method="POST" action="{{ route('admin.services.extend', $service->id) }}" style="margin-top: 5px;">
                            @csrf
                            <button type="submit" class="admin-btn-extend">연장</button>
                        </form>

                        <form method="POST" action="{{ route('admin.services.destroy', $service->id) }}" onsubmit="return confirm('정말 삭제하시겠습니까?')" style="margin-top: 5px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn-delete">삭제</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
