<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>서비스 점검 중</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800 flex items-center justify-center min-h-screen">
    <div class="text-center px-6">
        <div class="mb-8">
            <img src="{{ asset('storage/uploads/68527e454e25e.png') }}" alt="Hostyle Logo" class="mx-auto w-48">
        </div>
        <h1 class="text-5xl font-bold text-gray-600 mb-4">503</h1>
        <p class="text-xl font-semibold mb-2">서비스 점검 중입니다.</p>
        <p class="text-gray-600 mb-6">
            현재 서버 점검 또는 유지보수 중입니다. <br>
            잠시 후 다시 접속해 주세요.
        </p>
        <a href="{{ url()->previous() }}"
           class="inline-block px-5 py-3 bg-indigo-600 text-white font-medium rounded hover:bg-indigo-700 transition">
            이전 페이지로 돌아가기
        </a>
    </div>
</body>
</html>
