<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use \Stripe\Stripe;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Get plan list from strip
     * @return array[]
     */
    public function retrievePlans() {
        $key = \config('services.stripe.secret');
        $stripe = new \Stripe\StripeClient($key);
        $plansraw = $stripe->plans->all();
        $plans = $plansraw->data;
        
        foreach($plans as $plan) {
            $prod = $stripe->products->retrieve(
                $plan->product,[]
            );
            $plan->product = $prod;
        }
        return $plans;
    }
    /**
     * Show the subscription form for purchse
     * @return view
     */
    public function showSubscription() {
        $user = Auth::user();
        if($user->subscribed('default')) {

           $returnData = [
                'status' => 'error',
                'msg' => trans('message.you_already_subscribe'),
            ];
            return redirect('dashboard')->with($returnData);
        }
        $plans = $this->retrievePlans();
        return view('stripe.subscribe', [
            'type'=>'create',
            'user'=>$user,
            'intent' => $user->createSetupIntent(),
            'plans' => $plans
        ]);
    }
    /**
     * Set subscription for customer 
     * @param Request $request
     * @return array[]
     */
    public function processSubscription(Request $request)
    {
        $user = Auth::user();
        $paymentMethod = $request->input('payment_method');
                   
        $user->createOrGetStripeCustomer();
        $user->addPaymentMethod($paymentMethod);
        $plan = $request->input('plan');
        try {
           $user->newSubscription('default', $plan)->create($paymentMethod, [
               'email' => $user->email
           ]);
           $returnData = [
                'status' => 'success',
                'msg' => trans('message.subscription_started_successfully'),
            ];
            return redirect('dashboard')->with($returnData);

        } catch (\Exception $e) {
            return back()->with(['status'=>'error','msg' => 'Error creating subscription. ' . $e->getMessage()]);
        }       
    }
    /**
     * cancel subscription for customer 
     * @param Request $request
     * @return array[]
     */
    public function cancelSubscription(Request $request)
    {
        $user = Auth::user();
        try {
            $user->subscription('default')->cancel();

           $returnData = [
                'status' => 'success',
                'msg' => trans('message.subscription_cancelled_successfully'),
            ];
            return redirect('dashboard')->with($returnData);
        } catch (\Exception $e) {
        return back()->with(['status'=>'error','msg' => 'Error creating subscription. ' . $e->getMessage()]);
        } 
    }
    /**
     * show update subscription form to the customer
     * @param Request $request
     * @return view
     */
    public function updateSubscription() {
        $user = Auth::user();
        $plans = $this->retrievePlans();
        return view('stripe.subscribe', [
            'type'=>'update',
            'user'=>$user,
            'intent' => $user->createSetupIntent(),
            'plans' => $plans
        ]);
    }
    /**
     * change subscription form to the customer
     * @param Request $request
     * @return array[]
     */
    public function changeSubscription(Request $request)
    {
        $user = Auth::user();
        try {
            if($user->subscribed('default')) {
                $user->subscription('default')->noProrate()->swap($request->plan);

                $returnData = [
                    'status' => 'success',
                    'msg' => trans('message.subscription_updated_successfully'),
                ];
                return redirect('dashboard')->with($returnData);
            } else {
                //return redirect('show-subscription')->with(['status'=>'error','msg'=>trans('message.your_subscription_is_expired_or_you_do_not_have_active_plan_please_subscribe')]);
            }
        } catch (\Exception $e) {
        return back()->with(['status'=>'error','msg' => 'Error creating subscription. ' . $e->getMessage()]);
        } 
    }
}
