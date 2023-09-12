<?php
namespace App\Services\Payment;
use App\Models\Subscription;
use App\Models\Paymant;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Response;
use Stripe\StripeClient;
use Symfony\Component\HttpFoundation\Response;
class StripePayment{
public function createCheckoutSession(Subscription $subscription):Response{
   $stripe=App::make(StripeClient::class);
   
    $checkout_session= $stripe->checkout->sessions->create([
        'line_items'=>[
            [
            'price_data'=>[
                'currency'=>'usd',
                'product_data'=>[
                    'name'=>$subscription->plan->name,

                ],
                'unit_amount'=>$subscription->plan->price*100,
            ],
            'quantity'=>$subscription->expiers_at->diffInMonths($subscription->crete_at),
        ],
    ],         
          'metadata'=>[
            'subscription_id'=>$subscription->id,
          ],
        'mode'=>'payment', 
        'success_url'=>route('payments.success',$subscription->id),
        'cancel_url'=>route('payments.cancel',$subscription->id),




       ]);

       Payment::forceCreate([
        'user_id'=>Auth::id(),
        'subscription_id'=>$subscription->id,
        'amount'=>$subscription->price,
        'currency_code'=>'usd',
        'payment_gateway'=>'stripe',
        'gateway_reference_id'=>$checkout_session->id,
        'data'=>$checkout_session,
       ]);
       return redirect()->away($checkout_session->url );

}









}