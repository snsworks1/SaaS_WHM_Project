<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>{{ $code }} 오류</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen flex flex-col justify-center items-center">
        <img src="{{ asset('storage/uploads/68527e454e25e.png') }}" alt="Hostyle 로고" class="w-32 mb-6">

        <h1 class="text-5xl font-bold text-indigo-600 mb-2">{{ $code }}</h1>
        <p class="text-lg text-gray-600 text-center max-w-md">
            {{ $message }}
        </p>

        <a href="{{ url('/') }}"
           class="mt-8 inline-block px-6 py-3 bg-indigo-600 text-white font-medium rounded hover:bg-indigo-700 transition">
            홈으로 돌아가기
        </a>
    </div>
</body>
</html>
