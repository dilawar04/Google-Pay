<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Laravel\Cashier\Cashier;
use Illuminate\Support\Facades\Http;
use Google\ApiCore\ApiException;
use Google\Apis\PaymentsResellerSubscription\V1\SubscriptionPurchase;
use Google\Apis\PaymentsResellerSubscription\V1\SubscriptionsService;

class OrderController extends Controller
{
    public function checkout(){
        return view('checkout');
    }

    public function payment(Request $request){
        $googlePay = json_decode($request['data']['paymentMethodData']['tokenizationData']['token'], true);
        $order = new Order;
        $order->user_id = auth()->user()->id;
        $order->amount = $request->amount;
        $order->discount = 0;
        $order->status = 1;
  if($order->save()){
        $transactions = new Transaction;
        $transactions->order_id = $order->id;
        $transactions->transaction_id = $googlePay['id'];
        $transactions->type = $request['data']['paymentMethodData']['tokenizationData']['type'] ?? '';
        $transactions->type = 1;
        $transactions->save();
         return response(['success' => true, 'url' => 'success']);
 }else{
        return response(['success' => false]);
}
}

public function purchase($user, $product) {
    $price = $product->price * 100; // Google Pay requires the price in cents
    $environment = config('google-pay.environment');
    $merchantId = config('google-pay.merchant_id');
    $merchantSecret = config('google-pay.merchant_secret');
    $merchantName = config('google-pay.merchant_name');

    $paymentToken = $user->paymentMethods()->where('type', 'google_pay')->first()->payment_token;

    $subscriptionsService = new SubscriptionsService();
    $subscriptionsService->setAccessToken($paymentToken);
    $subscriptionPurchase = new SubscriptionPurchase();
    $subscriptionPurchase->setSku($product->id);
    $subscriptionPurchase->setStartDate(date('Y-m-d\TH:i:s\Z'));
    $subscriptionPurchase->setPlanQuantity(1);

    try {
        $subscriptionsService->subscriptions->purchase($merchantId, $subscriptionPurchase, ['merchantName' => $merchantName]);
    } catch (ApiException $e) {
        throw new Exception($e->getMessage());
    }
}
}
