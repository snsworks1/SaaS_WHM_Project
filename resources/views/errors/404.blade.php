<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>페이지를 찾을 수 없습니다</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800 flex items-center justify-center min-h-screen">
    <div class="text-center px-6">
        <!-- 로고 -->
        <div class="mb-8">
            <img src="{{ asset('storage/uploads/68527e454e25e.png') }}" alt="Hostyle Logo" class="mx-auto w-48">
        </div>

        <!-- 404 내용 -->
        <h1 class="text-5xl font-bold text-indigo-600 mb-4">404</h1>
        <p class="text-xl font-semibold mb-2">페이지를 찾을 수 없습니다.</p>
        <p class="text-gray-600 mb-6">요청하신 페이지가 존재하지 않거나 삭제되었습니다.</p>

        <!-- 돌아가기 버튼 -->
        <a href="{{ url('/') }}"
           class="inline-block px-5 py-3 bg-indigo-600 text-white font-medium rounded hover:bg-indigo-700 transition">
            홈으로 돌아가기
        </a>
    </div>
</body>
</html>
