@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-2xl shadow-md">
    <h2 class="text-2xl font-bold mb-6">새로운 플랜 생성</h2>

    <form method="POST" action="{{ route('admin.plans.store') }}">
        @csrf

        <!-- 플랜명 -->
        <div class="mb-4">
            <label class="block font-medium mb-1">플랜명 (WHM 패키지명)</label>
            <input type="text" name="name" class="w-full border rounded p-2" required>
        </div>

        <!-- 가격 -->
        <div class="mb-4">
            <label class="block font-medium mb-1">가격 (원)</label>
            <input type="number" name="price" class="w-full border rounded p-2" required>
        </div>

        <!-- 디스크 용량 -->
        <div class="mb-4">
            <label class="block font-medium mb-1">디스크 용량 (GB)</label>
            <input type="number" name="disk_size" class="w-full border rounded p-2" required>
        </div>

        <!-- 설명 -->
        <div class="mb-4">
            <label class="block font-medium mb-1">설명</label>
            <textarea name="description" class="w-full border rounded p-2"></textarea>
        </div>

        <hr class="my-6">

        <h3 class="text-xl font-semibold mb-4">리소스 제한</h3>

        <div class="grid grid-cols-2 gap-4">

            <div>
                <label class="block font-medium mb-1">FTP 계정 수</label>
                <input type="number" name="ftp_accounts" class="w-full border rounded p-2" value="1" required>
            </div>

            <div>
                <label class="block font-medium mb-1">이메일 계정 수</label>
                <input type="number" name="email_accounts" class="w-full border rounded p-2" value="5" required>
            </div>

            <div>
                <label class="block font-medium mb-1">DB 수</label>
                <input type="number" name="sql_databases" class="w-full border rounded p-2" value="1" required>
            </div>

            <div>
                <label class="block font-medium mb-1">메일링 리스트 수</label>
                <input type="number" name="mailing_lists" class="w-full border rounded p-2" value="0" required>
            </div>

            <div>
                <label class="block font-medium mb-1">시간당 이메일 발송 제한</label>
                <input type="number" name="max_email_per_hour" class="w-full border rounded p-2" value="50" required>
            </div>

            <div>
                <label class="block font-medium mb-1">이메일당 최대 용량 (MB)</label>
                <input type="number" name="email_quota" class="w-full border rounded p-2" value="500" required>
            </div>

        </div>

        <div class="mt-6">
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                플랜 생성
            </button>
        </div>
    </form>
</div>
@endsection
