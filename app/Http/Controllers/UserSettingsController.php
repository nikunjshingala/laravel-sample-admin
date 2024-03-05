<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Components\UserSettingsComponent;
use App\Http\Controllers\SubscriptionController;
use Laravel\Cashier\Cashier;
use \Stripe\Stripe;
class UserSettingsController extends Controller
{
    public function __construct()
    {
        $this->userSettingsComponent = new UserSettingsComponent;
        $this->subscriptionController = new SubscriptionController;
    }
    /**
     * Used to show the logedin user details
     *
     * @param Request $request
     * @return view
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $postBackData = User::where('id',$user->id)->first();
        $timezonelist = config('constants.REGIONS_LIST');
        $planList = $this->subscriptionController->retrievePlans();
        $userSubscription = $user->subscriptions->first();
        $currentPlanDetails = collect();
        foreach($planList AS $singlePlan) {
            if($singlePlan->id == $userSubscription->stripe_price){
                $currentPlanDetails = $singlePlan;
                break;
            }
        }
        return view('user_settings.index',compact('postBackData','timezonelist','currentPlanDetails'));
    }
    /**
     * Used to store the logedin user detail
     *
     * @param Request $request
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function userDetailsUpdate(Request $request)
    {
        $rules = [
            'email' => 'required',
            'name' => 'required',
            'user_type' => 'required',
            'gender' => 'required',
            'aboutme' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            $message = $validator->messages()->all();
            $returnData = [
                'status' => 'error',
                'msg' => $message[0]
            ];
            
            return redirect('user-settings')->with($returnData);
        }
        
        $returnData = $this->userSettingsComponent->userDetailsUpdate($request);
        
        return redirect('user-settings')->with($returnData);
    }
    /**
     * Used to change password of logedin user
     *
     * @param Request $request
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function userPasswordChange(Request $request)
    {
        $rules = [
            'old_password' => 'required',
            'new_password' => 'required',
            'cnew_password' => 'required|same:new_password'
        ];
        
        $data = [
            'old_password' => $request->old_password,
            'new_password' => $request->new_password,
            'cnew_password' => $request->cnew_password
        ];
        
        //$this->validate($request, $rules);
        
        // validate the request data
        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()) {
            $message = $validator->messages()->all();
            $returnData = [
                'status' => 'error',
                'msg' => $message[0]
            ];
            
            return redirect('user-settings')->with($returnData);
        }
        
        // get user based on user id
        $userId = Auth::user()->id;
        
        $returnData = [
            'status' => 'error',
            'msg' => trans('message.something_went_wrong')
        ];
        
        $returnData = $this->userSettingsComponent->userPasswordChange($request);
        return redirect('user-settings')->with($returnData);
    }
}
