<div class="flex justify-center mb-6 sm:mb-10">
    <div class="flex items-center overflow-x-auto max-w-full px-2 sm:px-0 space-x-2 sm:space-x-6">

        {{-- Step 1 --}}
        @include('components.step-icon', ['index' => 1, 'label' => '플랜 선택', 'step' => $step])
        <div class="w-6 sm:w-8 h-1 {{ $step >= 2 ? 'bg-green-400' : 'bg-gray-300' }}"></div>

        {{-- Step 2 --}}
        @include('components.step-icon', ['index' => 2, 'label' => '기간 선택', 'step' => $step])
        <div class="w-6 sm:w-8 h-1 {{ $step >= 3 ? 'bg-green-400' : 'bg-gray-300' }}"></div>

        {{-- Step 3 --}}
        @include('components.step-icon', ['index' => 3, 'label' => '정보 입력', 'step' => $step])
        <div class="w-6 sm:w-8 h-1 {{ $step >= 4 ? 'bg-green-400' : 'bg-gray-300' }}"></div>

        {{-- Step 4 --}}
        @include('components.step-icon', ['index' => 4, 'label' => '결제서 확인', 'step' => $step])
        <div class="w-6 sm:w-8 h-1 {{ $step >= 5 ? 'bg-green-400' : 'bg-gray-300' }}"></div>

        {{-- Step 5 --}}
        @include('components.step-icon', ['index' => 5, 'label' => '결제 완료', 'step' => $step])

    </div>
</div>
