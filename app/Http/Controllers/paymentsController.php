<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Services\Payment\StripePayment;
use Error;
use Illuminate\Http\Request;
use Response;
use Stripe\Charge;
use Stripe\StripeClient;

class paymentsController extends Controller
{
    public function create(StripePayment $stripe,Subscription $subscription){
        // return view('checkout',[
        //     'subscription'=>$subscription
        // ]);
       
    //    return redirect()->away($checkout_session->url );
return $stripe->createCheckoutSession($subscription);

}
    public function store(Request $request){
          $subscription=Subscription::findOrFail($request->subscription_id);
   
        $stripe=new StripeClient(config('services.stripe.secret_key'));
       
        try {
          
        
            // Create a PaymentIntent with amount and currency
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' =>$subscription->price*100,
                'currency' => 'usd',
                // In the latest version of the API, specifying the `automatic_payment_methods` parameter is optional because Stripe enables its functionality by default.
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);
        
            return [
                'clientSecret' => $paymentIntent->client_secret,
            ];
        
        } catch (Error $e) {
            return Response::json(['error' => $e->getMessage(),],
       500
        );
        }
    }
       
       
       
       
//     $stripe->charges->create([
//     'amount'=>$subscription->price*100,
//     'currency'=>'usd',
//     'source'=>$subscription->id,
//     'description'=>$subscription->name,
//   ]);

// \Stripe\Stripe::setApiKey(config('services.stripe.secret_key'));


//      //   \Stripe\Stripe::setApiKey(config('services.stripe.secret_key'));
//      $checkout_session=Charge::create([//\Stripe\Checkout\Session::create([
//     //  'line_items'=>[[
//     //     'price'=>'{{PRICE_ID}}',
//     //     'quantity'=>1,

//     //  ]],
//        'mode'=>'payment',
//        'client_reference_id'=>$subscription->id,
//        'customer_email'=>$subscription->user->email,
//        'success_url'=>route('payments.success'),
//        'cancel_url'=>route('payments.cancel'),
//      ]);
//     }
    public function success(Request $request){
        return view('payments.cancel');

       
        //return $request->all();
        // $stripe=new \Stripe\StripeClient(config('services.stripe.secret_key'));
        //  $stripe->paymentIntent->retrieve(
        //   $request->input('payment_intent'),
        //   []
        //  );
      
    }
    public function cancel(Request $request){
        return view('payments.cancel');
        // return $request->all();
    }
}
