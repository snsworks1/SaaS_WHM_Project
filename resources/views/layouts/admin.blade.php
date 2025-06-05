<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Admin - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex">
        <aside class="w-64 bg-blue-800 text-white p-4">
            <h1 class="text-2xl font-bold mb-6">Admin Panel</h1>
            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block hover:bg-blue-700 rounded p-2">대시보드</a>
                <a href="{{ route('admin.plans.index') }}" class="block hover:bg-blue-700 rounded p-2">플랜 관리</a>
            </nav>
        </aside>

        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
