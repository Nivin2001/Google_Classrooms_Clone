<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Paymant;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class StripeController extends Controller
{
    public function __invoke(Request $request,StripeClient $stripe){
// This is your Stripe CLI webhook secret for testing your endpoint locally.
$endpoint_secret = 'whsec_9f17ff4373e0858e526e04db5c15cf7ccf3f29963a17e3575296a3146511221d';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
  $event = \Stripe\Webhook::constructEvent(
    $payload, $sig_header, $endpoint_secret
  );
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  return response('',400);
} catch(\Stripe\Exception\SignatureVerificationException $e) {
  // Invalid signature
  return response('',400);

}

// Handle the event
switch ($event->type) {
  case 'checkout.session.async_payment_failed':
    $session = $event->data->object;
    break;
  case 'checkout.session.async_payment_succeeded':
    $session = $event->data->object;
    break;
  case 'checkout.session.completed':
    $session = $event->data->object;
    Paymant::where('gateway_reference_id',$session->id)->update([
        'gateway_reference_id'=>$session->payment_intent,
    ]);
    break;
  case 'checkout.session.expired':
    $session = $event->data->object;
    break;
  case 'payment_intent.amount_capturable_updated':
    $paymentIntent = $event->data->object;
    break;
  case 'payment_intent.canceled':
    $paymentIntent = $event->data->object;
    break;
  case 'payment_intent.created':
    $paymentIntent = $event->data->object;
    break;
  case 'payment_intent.partially_funded':
    $paymentIntent = $event->data->object;
    break;
  case 'payment_intent.payment_failed':
    $paymentIntent = $event->data->object;
    break;
  case 'payment_intent.processing':
    $paymentIntent = $event->data->object;
  case 'payment_intent.requires_action':
    $paymentIntent = $event->data->object;
    break;
  case 'payment_intent.succeeded':
    $paymentIntent = $event->data->object;
 $payment=Paymant::where('gateway_reference_id',$paymentIntent->id)->first();
 $payment->forceFill([
    'status'=>'completed'
 ])->save();
 $Subscription=Subscription::where('id',$payment->subscription_id)->first();
 $Subscription->update([
    'status'=>'active',
    'expires_at'=>now()->addMonth(3)
 ]);
 break;
  default:
    echo 'Received unknown event type ' . $event->type;
}

return response('',200) ;   
}
}