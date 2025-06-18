<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Admin - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex">

    <!-- 사이드바 -->
    <div class="flex flex-col w-64 h-screen px-4 py-8 bg-white border-r dark:bg-gray-900 dark:border-gray-700">
        <h2 class="text-3xl font-semibold text-gray-800 dark:text-white mb-6">관리자</h2>

        <nav class="flex-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 bg-gray-200 rounded-md dark:bg-gray-800 dark:text-white mb-2">
                대시보드
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 mb-2">
                회원 관리
            </a>
            <a href="{{ route('admin.plans.index') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 mb-2">
                플랜 관리
            </a>
            <a href="{{ route('admin.services.index') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 mb-2">
                서버 관리
            </a>
            <a href="{{ route('admin.whm-servers.index') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700">
                WHM 서버 관리
            </a>
        
            <a href="{{ route('admin.notices.index') }}" class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700">
                공지사항
            </a>
        </nav>
    </div>

    <!-- 본문 영역 -->
    <div class="flex-1 p-8">
        @yield('content')
    </div>

</body>
</html>
