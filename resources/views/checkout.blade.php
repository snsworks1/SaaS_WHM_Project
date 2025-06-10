<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>결제 - {{ $plan->name }}</title>
    <script src="https://js.tosspayments.com/v1/payment"></script>
</head>
<body>
    <h1>{{ $plan->name }} 결제</h1>
    <p>가격: {{ $plan->price }}원</p>

    <button id="payment-button">결제하기</button>

    <script>
        const clientKey = "{{ config('services.toss.client_key') }}";
        const tossPayments = TossPayments(clientKey);
        const button = document.getElementById('payment-button');

        button.addEventListener('click', () => {
            const orderId = "order_" + Date.now();
            const amount = {{ $plan->price }};

            tossPayments.requestPayment('카드', {
                amount: amount,
                orderId: orderId,
                orderName: "{{ $plan->name }}",
                customerName: "테스트사용자",
                successUrl: "{{ url('/checkout/confirm') }}",
                failUrl: "{{ url('/checkout/fail') }}",
            });
        });
    </script>
</body>
</html>
