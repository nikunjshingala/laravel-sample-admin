<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDeviceList;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *     path="/get-profile",
     *     tags={"Profile"},
     *     summary="Get profile details",
     *     operationId="getprofile",
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
     * )
     */
    public function getProfile(){
        $user = auth()->user();
        dd($user);
        $imageName = $user->profile;
        if(empty($user->profile)) 
            $imageName = 'default.png';
    
        $image = asset('storage/user_profile/'.$imageName);
        $user['profileUrl'] = $image;
        $user  = collect($user)->except('password', 'created_at', 'updated_at','email_verified_at');
        return response()->json($user, 200);
    }
    /**
     * @OA\Post(
     ** path="/update-profile",
     *   tags={"Profile"},
     *   summary="Update Profile",
     *   operationId="update-profile",
     *   security={{"bearer_token":{}}},
     *   *  @OA\RequestBody(
     *         description="Update Profile",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required = {"email", "name"},
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="Enter email",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     description="Enter name",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="profile",
     *                     description="Select profile photo",
     *                     type="file",
     *                     format="file",
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
    public function updateProfile(Request $request){
        $rules = [
            'email' => 'required',
            'name' => 'required',
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
        $updArr['name'] = $request->name;
        if ($request->hasFile('profile')) {
            
            // get current time and append the upload file extension to it,
            // then put that name to $profile variable. 
            
            //$profileOrignalName = $request->profile->getClientOriginalName();
            $profile = time().'.'.$request->profile->getClientOriginalExtension();
            
            /*
             take the select file and move it public directory and make brands
             folder if doesn't exsit then give it that unique name.
             */
            $request->profile->move(storage_path('user_profile'), $profile);
            $updArr['profile'] = $profile;
        }
        
        $postBackData = User::where('id',Auth::user()->id)->update($updArr);
        $user = User::where('id',Auth::user()->id)->first();
        $user  = collect($user)->except('password', 'created_at', 'updated_at','email_verified_at');
        $imageName = $user->profile;
        if(empty($user->profile)) 
            $imageName = 'default.png';
    
        $image = asset('storage/user_profile/'.$imageName);
        $user['profileUrl'] = $image;
        $returnData = array();
        $returnData['data'] = $user;
        $returnData['status'] = 'success';
        $returnData['msg'] = 'Details updated successfully.';
        return response()->json($returnData);
    }
    /**
     * @OA\Post(
     ** path="/change-password",
     *   tags={"Profile"},
     *   summary="Change your Password",
     *   operationId="change-password",
     *   security={{"bearer_token":{}}},
     *   *  @OA\RequestBody(
     *         description="Change Password",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required = {"old_password", "new_password", "cnew_password"},
     *                 type="object",
     *                 @OA\Property(
     *                     property="old_password",
     *                     description="Enter current password",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="new_password",
     *                     description="Enter new password",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="cnew_password",
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
    public function changePassword(Request $request){
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
            
            return response()->json($returnData);
        }
        
        // get user based on user id
        $userId = Auth::user()->id;
        
        $returnData = [
            'status' => 'error',
            'msg' => 'Somthing went wrong, please try again.'
        ];
        
        // check $userId is numeric or not
        if (is_numeric($userId)) {
            
            $users = User::where('id', $userId)->first();
            
            // update user
            if ($users->count() > 0) {
                // check if old password match with database or not
                if (! Hash::check($request->old_password, $users->password)) {
                    
                    $returnData = [
                        'status' => 'error',
                        'msg' => 'Old password not match with the system'
                    ];
                    
                    
                    return response()->json($returnData);
                }
                
                $users->password = bcrypt($request->new_password);
                
                if($users->save()){
                    $returnData = [
                        'status' => 'success',
                        'msg' => 'Password Change successfully.'
                    ];
                }
                
                return response()->json($returnData);
            }
        }
        
        
        return response()->json($returnData);
    }
    /**
    * @OA\Post(
    * path="/save-update-device",
    * summary="Save Device For User",
    * description="Save Device for user",
    * operationId="save-update-device",
    * tags={"User"},
    * security={ {"bearer_token": {} }},
    * @OA\RequestBody(
    *         description="Update Profile",
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 required = {"user_id", "api_level", "brand", "build_number", "device_country", "device_name", "manufacturer", "model", "system_name", "system_version", "version", "device_token"},
    *                 type="object",
    *                 @OA\Property(
    *                     property="user_id",
    *                     description="User Id",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="api_level",
    *                     description="Enter api level",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="brand",
    *                     description="Enter brand",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="build_number",
    *                     description="Enter build number",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="device_country",
    *                     description="Enter device country",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="device_name",
    *                     description="Enter device name",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="manufacturer",
    *                     description="Enter manufacturer",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="model",
    *                     description="Enter model",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="system_name",
    *                     description="Enter system name",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="system_version",
    *                     description="Enter system version",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="version",
    *                     description="Enter version",
    *                     type="string",
    *                 ),
    *                 @OA\Property(
    *                     property="device_token",
    *                     description="Enter device token",
    *                     type="string",
    *                 ),
    *             )
    *         )
    *     ),
    *   @OA\Response(
    *         response="200",
    *         description="ok",
    *         content={
    *             @OA\MediaType(
    *                 mediaType="application/json",
    *                 @OA\Schema(
    *                     @OA\Property(
    *                         property="status_code",
    *                         type="integer",
    *                         description="The response code"
    *                     ),
    *                     @OA\Property(
    *                         property="message",
    *                         type="string",
    *                         description="The response message"
    *                     ),
    *                     @OA\Property(
    *                         property="data",
    *                         type="array",
    *                         description="The response data",
    *                         @OA\Items
    *                     ),
    *                 )
    *             )
    *         }
    *     ), 
    *     @OA\Response(
    *         response="400",
    *         description="Validation errors!",
    *         content={
    *             @OA\MediaType(
    *                 mediaType="application/json",
    *                 @OA\Schema(
    *                     @OA\Property(
    *                         property="status_code",
    *                         type="integer",
    *                         description="The response code"
    *                     ),
    *                     @OA\Property(
    *                         property="message",
    *                         type="string",
    *                         description="The response message"
    *                     ),
    *                     @OA\Property(
    *                         property="data",
    *                         type="array",
    *                         description="The response data",
    *                         @OA\Items
    *                     )
    *                 )
    *             )
    *         }
    *     ), 
    *     @OA\Response(
    *         response="401",
    *         description="Invalid credential",
    *         content={
    *             @OA\MediaType(
    *                 mediaType="application/json",
    *                 @OA\Schema(
    *                     @OA\Property(
    *                         property="status_code",
    *                         type="integer",
    *                         description="The response code"
    *                     ),
    *                     @OA\Property(
    *                         property="message",
    *                         type="string",
    *                         description="The response message"
    *                     ),
    *                     @OA\Property(
    *                         property="data",
    *                         type="array",
    *                         description="The response data",
    *                         @OA\Items
    *                     ),
    *                 )
    *             )
    *         }
    *     ), 
    * )
    */
    public function saveUpdateDevice(Request $request){
        $user = UserDeviceList::updateOrCreate([
            //Add unique field combo to match here
            //For example, perhaps you only want one entry per user:
            'user_id'   => Auth::user()->id,
        ],[
            'api_level'     => $request->get('api_level'),
            'brand' => $request->get('brand'),
            'build_number'    => $request->get("build_number"),
            'device_country'   => $request->get('device_country'),
            'device_name'       => $request->get('device_name'),
            'manufacturer'   => $request->get('manufacturer'),
            'model'    => $request->get('model'),
            'system_name'    => $request->get('system_name'),
            'system_version'    => $request->get('system_version'),
            'version'    => $request->get('version'),
            'device_token'    => $request->get('device_token'),
        ]);
        return response()->json($user, 200);
    }
}
