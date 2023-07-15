<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Document</title>
</head>
<body>
<div id="google-pay-button"></div>
<script src="https://pay.google.com/gp/p/js/pay.js"></script>

<!-- <div id="container"></div> -->
</body>
@php
$total = 111;
@endphp
<script>
    const paymentsClient = new google.payments.api.PaymentsClient({
    environment: '{{ config('google-pay.environment') }}'
});

const paymentDataRequest = {
    allowedPaymentMethods: ['CARD', 'TOKENIZED_CARD'],
    transactionInfo: {
        currencyCode: 'USD',
        totalPriceStatus: 'FINAL',
        totalPrice: '{{ $total }}'
    },
    merchantInfo: {
        merchantId: '{{ config('google-pay.merchant_id') }}',
        merchantName: '{{ config('google-pay.merchant_name') }}'
    }
};


const googlePayButton = paymentsClient.createButton({
    onClick: () => {
        paymentsClient.loadPaymentData(paymentDataRequest)
            .then(paymentData => {
                const token = paymentData.paymentMethodData.tokenizationData.token;
                // Save the payment token to your database
            })
            .catch(err => {
                console.error(err);
            });
    }
});


document.getElementById('google-pay-button').appendChild(googlePayButton);

</script>
<!-- <script async
  src="https://pay.google.com/gp/p/js/pay.js"
  onload="onGooglePayLoaded()"></script> -->
</html>