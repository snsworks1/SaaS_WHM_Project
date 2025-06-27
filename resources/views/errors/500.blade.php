
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>500 - 서버 오류</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800 flex items-center justify-center min-h-screen">
    <div class="text-center px-6">
        <div class="mb-8">
            <img src="{{ asset('storage/uploads/68527e454e25e.png') }}" alt="Hostyle Logo" class="mx-auto w-48">
        </div>
       <h1 class="text-5xl font-extrabold text-red-600 mb-4">500</h1>
        <h2 class="text-2xl font-semibold mb-2">서버 오류가 발생했습니다</h2>
        <p class="text-gray-600 mb-6">
            요청을 처리하는 도중 문제가 발생했습니다.<br>
            잠시 후 다시 시도해주세요.
        </p>
        <a href="{{ url()->previous() }}"
           class="inline-block px-5 py-3 bg-indigo-600 text-white font-medium rounded hover:bg-indigo-700 transition">
            이전 페이지로 돌아가기
        </a>
    </div>
</body>
</html>
