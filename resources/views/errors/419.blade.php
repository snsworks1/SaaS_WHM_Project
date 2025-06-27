<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>페이지 만료</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800 flex items-center justify-center min-h-screen">
    <div class="text-center px-6">
        <div class="mb-8">
            <img src="{{ asset('storage/uploads/68527e454e25e.png') }}" alt="Hostyle Logo" class="mx-auto w-48">
        </div>
        <h1 class="text-5xl font-bold text-gray-600 mb-4">419</h1>
        <p class="text-xl font-semibold mb-2">페이지가 만료되었습니다.</p>
        <p class="text-gray-600 mb-6">
            너무 오래 기다렸거나, 보안 토큰이 유효하지 않습니다.<br>
            다시 시도해주세요.
        </p>
        <a href="{{ url()->previous() }}"
           class="inline-block px-5 py-3 bg-indigo-600 text-white font-medium rounded hover:bg-indigo-700 transition">
            이전 페이지로 돌아가기
        </a>
    </div>
</body>
</html>
