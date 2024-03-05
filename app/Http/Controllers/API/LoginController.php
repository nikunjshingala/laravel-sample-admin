<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\User;
use Mail;
use Carbon\Carbon;


class LoginController extends Controller
{

    /**
     * @OA\Post(
     ** path="/login",
     *   tags={"User"},
     *   summary="After Login response get the token and add to bearer_token",
     *   operationId="authLogin",
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function loginUser(Request $request)
    {
        $login_credentials=[
            'email'=>$request->email,
            'password'=>$request->password,
        ];
        if(auth()->attempt($login_credentials)){
            //generate the token for the user
            $user = auth()->user();
            $user['token']= auth()->user()->createToken(config('constants.SITE_NAME'))->accessToken;
            //now return this token on success login attempt
            return response()->json($user, 200);
        }
        else{
            //wrong login credentials, return, user not authorised to our system, return error code 401
            return response()->json(['error' => 'UnAuthorised Access'], 401);
        }
    }
    /**
     * @OA\Post(
     ** path="/forgot-password",
     *   tags={"User"},
     *   summary="Forgot Password Sent OTP To User Email",
     *   operationId="forgot-password",
     *   *  @OA\RequestBody(
     *         description="Forgot Password",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required = {"email"},
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="Enter email",
     *                     type="string",
     *                 ),
     *             )
     *         )
     *     ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    
    public function forgotPassword(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->all();
            $returnData = [
                'status' => 'error',
                'msg' => $message[0]
            ];
            
            return response()->json($returnData);
        }
        $email = $request->email;
        $user = User::where('email',$email)->first();
        $returnData = array();
        if(isset($user) && $user->count() > 0) {
            $six_digit_random_number = random_int(100000, 999999);
            $data = array();
            $data['otp'] = $six_digit_random_number;
            Mail::send('reset_password_otp', $data, function($message) use($email) {
                $message->to($email, config('constants.MAIL_FROM_NAME'))->subject("Admin Panel");
                $message->from(config('constants.MAIL_FROM_ADDRESS'), config('constants.MAIL_FROM_NAME'));
            });
            $user->otp = $six_digit_random_number;
            $user->otp_sent = date("Y-m-d H:i:s");
            $user->save();
            $returnData['status'] = 'success';
            $returnData['msg'] = trans('message.password_reset_mail_sent_successfully');
        } else {
            $returnData = [
                'status' => 'error',
                'msg' => trans('message.no_user_found_with_the_given_email_id')
            ];
        }
        return response()->json($returnData);
    }
    /**
     * @OA\Post(
     ** path="/reset-password",
     *   tags={"User"},
     *   summary="Reset Password Using OTP",
     *   operationId="reset-password",
     *   *  @OA\RequestBody(
     *         description="Reset Password",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required = {"email","otp","password","confirm_password"},
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="Enter email",
     *                     type="string",
     *                 ),
     *                  @OA\Property(
     *                     property="otp",
     *                     description="Enter otp",
     *                     type="string",
     *                 ),
     *                  @OA\Property(
     *                     property="password",
     *                     description="Enter password",
     *                     type="string",
     *                 ),
     *                  @OA\Property(
     *                     property="confirm_password",
     *                     description="Enter confirm password",
     *                     type="string",
     *                 ),
     *             )
     *         )
     *     ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function resetPassword(Request $request)
    {

        $rules = [
            'otp' => 'required',
            'email' => 'required|email',
            'password' => 'required|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required',
        ];
        $returnData = array();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->all();
            $returnData['status'] = 'error';
            $returnData['msg'] = $message[0];
            return response()->json($returnData);
        }
        $email = $request->email;
        $otp = $request->otp;
        $user = User::where('email',$email)->first();
        if(isset($user) && $user->count() > 0) {
            if ($user->otp == $otp) {
                $startTime = Carbon::parse($user->otp_sent)->format('Y-m-d H:i:s');
                $endTime = Carbon::now();
                $totalDiff = $endTime->diffInMinutes($startTime);
                if($totalDiff < 60 ) {
                    $returnData['status'] = 'success';
                    $returnData['msg'] = 'Password reset successfully, Please login using new password.';
                    $user->password = bcrypt($request->password);
                    $user->otp = null;
                    $user->otp_sent = null;
                    $user->save();
                } else {
                    $user->otp = null;
                    $user->otp_sent = null;
                    $user->save();
                    $returnData['status'] = 'error';
                    $returnData['msg'] = 'OTP expired, please try again forgot password.';
                }
            } else {
                $returnData['status'] = 'error';
                $returnData['msg'] = 'Invalid OTP.';
            }

        } else {
            $returnData = [
                'status' => 'error',
                'msg' => 'No user found with the given email id.'
            ];
        }
        return response()->json($returnData, 200);
    }

    /**
     * @OA\Post(
     ** path="/logout",
     *   tags={"User"},
     *   summary="Logout user and invalidate token",
     *   operationId="logout",
     *   security={{"bearer_token":{}}},
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function logoutUser(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
