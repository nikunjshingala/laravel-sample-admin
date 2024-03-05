<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * 
     * Login user account
     * @param \Illuminate\Http\Request $request 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
            ->withInput()
            ->withErrors($validator);
        }
        $remember = false;
        if (isset($request->remember)) {
            $remember = true;
        }

        $user = User::where('email', $request->email)->where('status','!=','deleted')->first();
        if ($user) {
            if($user->status == 'inactive') {
                $returnData['status'] = 'error';
                $returnData['msg'] = trans('message.your_account_is_inactive_please_contact_to_admin');
                return redirect('login')->with($returnData);
            }
            if (Hash::check($request->password, $user->password)) {
                Auth::guard()->loginUsingId($user->id, $remember);
                if ($remember == true) {
                    Cookie::queue(Cookie::forever('email', $request->email));
                    Cookie::queue(Cookie::forever('password', $request->password));
                    Cookie::queue(Cookie::forever('remember', true));
                } else {
                    Cookie::queue(Cookie::forget('email'));
                    Cookie::queue(Cookie::forget('password'));
                    Cookie::queue(Cookie::forget('remember'));
                }
                $returnData['status'] = 'success';
                $returnData['msg'] = trans('message.user_login_successfully');
                return redirect('dashboard')->with($returnData);
            }
        }
        $returnData['status'] = 'error';
        $returnData['msg'] = trans('message.invalid_email_and_password');
        return redirect()->back()->with($returnData);
    }

    /**
     * 
     * Logout user from panel
     * @return \Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();
        $returnData['status'] = 'success';
        $returnData['msg'] = trans('message.user_logout_successfully');
        return redirect('login')->with($returnData);
    }
    /**
     * Used for the forgot password
     *
     * @param Request $request
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
 
        $status = Password::sendResetLink(
            $request->only('email')
        );
        $returnData = array();
        $returnData['status'] = 'success';
        $returnData['msg'] = trans('message.password_reset_link_sent_successfully');
        if(!$request->ajax()){
            return $status === Password::RESET_LINK_SENT
                        ? back()->with($returnData)
                        : back()->withErrors(['email' => __($status)]);
        } else {
            return $status === Password::RESET_LINK_SENT
            ? ($returnData)
            : (['msg' => __($status)]);
        }
    }
    /**
     * Used to restore the password
     *
     * @param Request $request
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function recoverPassword(Request $request)
    {

        $rules = [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
            ->withInput()
            ->withErrors($validator);
        }
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );
     
        $returnData = array();
        $returnData['status'] = 'success';
        $returnData['msg'] = trans('message.password_reset_successfully_please_login_using_new_password');
        return $status === Password::PASSWORD_RESET
                    ? redirect('login')->with($returnData)
                    : back()->withErrors(['email' => [__($status)]]);
    }
}
