<div class="flex justify-center mb-6 sm:mb-10">
    <div class="flex items-center overflow-x-auto max-w-full px-2 sm:px-0 space-x-2 sm:space-x-6">


        {{-- Step 1 --}}
        <div class="flex flex-col items-center">
            <div class="w-12 h-12 rounded-full border-4 
                {{ $step == 1 ? 'border-green-500 bg-green-500 text-white' : ($step > 1 ? 'border-green-300 bg-green-300 text-white' : 'border-gray-300 bg-gray-100 text-gray-600') }}
                flex items-center justify-center text-lg font-bold shadow">
                1
            </div>
            <div class="mt-2 text-sm font-medium text-gray-800">플랜 선택</div>
        </div>

        <div class="w-10 h-1 {{ $step >= 2 ? 'bg-green-300' : 'bg-gray-300' }}"></div>

        {{-- Step 2 --}}
        <div class="flex flex-col items-center">
            <div class="w-12 h-12 rounded-full border-4 
                {{ $step == 2 ? 'border-green-500 bg-green-500 text-white' : ($step > 2 ? 'border-green-300 bg-green-300 text-white' : 'border-gray-300 bg-gray-100 text-gray-600') }}
                flex items-center justify-center text-lg font-bold shadow">
                2
            </div>
            <div class="mt-2 text-sm font-medium text-gray-800">요금 확인</div>
        </div>

        <div class="w-10 h-1 {{ $step >= 3 ? 'bg-green-300' : 'bg-gray-300' }}"></div>

        {{-- Step 3 --}}
        <div class="flex flex-col items-center">
            <div class="w-12 h-12 rounded-full border-4 
                {{ $step == 3 ? 'border-green-500 bg-green-500 text-white' : 'border-gray-300 bg-gray-100 text-gray-600' }}
                flex items-center justify-center text-lg font-bold shadow">
                3
            </div>
            <div class="mt-2 text-sm font-medium text-gray-800">완료</div>
        </div>

    </div>
</div>
