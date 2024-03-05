<?php

namespace App\Http\Components;
use App\Models\User;
use DB;
use Hash;
use Auth;
use App;
class UserSettingsComponent
{
    /**
     * Used to store the logedin user detail
     *
     * @param Request $request
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function userDetailsUpdate($request)
    {
        $result = DB::transaction(function() use($request)
        {
            $updArr = array();
            $updArr['name'] = $request->name;
            $updArr['user_type'] = $request->user_type;
            $updArr['gender'] = $request->gender;
            $updArr['aboutme'] = $request->aboutme;
            $updArr['timezone'] = $request->timezone;
            $updArr['is_offer_news'] = isset($request->is_offer_news) ? $request->is_offer_news : 0;
            if ($request->hasFile('profile')) {
                
                // get current time and append the upload file extension to it,
                // then put that name to $profile variable. 
                
                $profile = time().'.'.$request->profile->getClientOriginalExtension();
                
                /*
                take the select file and move it public directory and make brands
                folder if doesn't exsit then give it that unique name.
                */
                $request->profile->move(storage_path('user_profile'), $profile);
                $updArr['profile'] = $profile;
            }
            $postBackData = User::where('id',Auth::user()->id)->update($updArr);

            $returnData['status'] = 'success';
            $returnData['msg'] = trans('message.details_updated_successfully');
            
            App::setLocale($request->language);
            session()->put('locale', $request->language);
            return $returnData;
        });
        return $result;
    }

     /**
     * Used to change password of logedin user
     *
     * @param Request $request
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function userPasswordChange($request)
    {
        $result = DB::transaction(function() use($request)
        {
            // get user based on user id
            $userId = Auth::user()->id;
            
            $returnData = [
                'status' => 'error',
                'msg' => trans('message.something_went_wrong')
            ];
            
            // check $userId is numeric or not
            if (is_numeric($userId)) {
                
                $users = User::where('id', $userId)->first();
                
                // update user
                if ($users->count() > 0) {
                    // check if old password match with database or not
                    if (!Hash::check($request->old_password, $users->password)) {
                        
                        $returnData = [
                            'status' => 'error',
                            'msg' => trans('message.old_password_not_match_with_the_system')
                        ];
                        return $returnData;
                    }
                    
                    $users->password = bcrypt($request->new_password);
                    
                    if($users->save()){
                        $returnData = [
                            'status' => 'success',
                            'msg' => trans('message.password_change_successfully')
                        ];
                    }
                    return $returnData;
                }
            }
            return $returnData;
        });
        return $result;
    }
}