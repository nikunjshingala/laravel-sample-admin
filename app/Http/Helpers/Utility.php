<?php
namespace App\Http\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\Permission;
use App\Models\Menus;
use App\Models\User;

class Utility
{
    static $skey = 'Web@OPTIMIZATION28042022';
    
    static $pageTitle = '';
    
    /**
     * safeb64Encode()
     * This function is used to encode into base64.
     *
     * @param : $string : String which you wan to encode.
     * @return Response
     */
    public static function safeb64Encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace([
            '+',
            '/',
            '='
        ], [
            '-',
            '_',
            ''
        ], $data);
        
        return $data;
    }

    /**
     * safeb64Decode()
     * This function is used to decode b64 safely.
     *
     * @param : $string String which you want to decode
     * @return return decode code.
     */
    public static function safeb64Decode($string)
    {
        $data = str_replace([
            '-',
            '_'
        ], [
            '+',
            '/'
        ], $string);
        
        $mod4 = strlen($data) % 4;
        
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    /**
     * This method will encrypt string
     *
     * @param string $value
     * @return boolean|string
     */
    public static function encode($value)
    {
        if (! $value) {
            return false;
        }
        
        $secret_key = self::$skey;
        $secret_iv = 'property@best00key!!';
        
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        
        $output = self::safeb64Encode(openssl_encrypt($value, $encrypt_method, $key, 0, $iv));
        
        return $output;
    }

    /**
     * This method will decrypt string
     *
     * @param string $value
     * @return boolean|string
     */
    public static function decode($value)
    {
        if (! $value) {
            return false;
        }
        
        $secret_key = self::$skey;
        $secret_iv = 'property@best00key!!';
        
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        
        $output = openssl_decrypt(self::safeb64Decode($value), $encrypt_method, $key, 0, $iv);
        
        return $output;
    }
    
    /**
     * Used for convert to json Out put
     *
     * @param int $status_code 1 for success,0 for fail ,101 for validation error
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function jsonOut($status_code, $message = '', $data = [], $code = '200')
    {
        return response()->json([
            'status' => $status_code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * used for getting menu permission data
     *
     * @param int $userId
     *
     * @return array $result
     */
    public static function getUserPermission($userId)
    {
        $permissionData = Permission::select("*")
                                    ->where('status', 1)
                                    ->where('user_id', $userId)
                                    ->get();
        $user = User::find($userId);
        if($user && $user->type == 'Admin') {
            $menuList = Menus::all();
            $allList = implode(',',$menuList->pluck('id')->toArray());
            $permissionData[0]->allow_all = $allList;
            $permissionData[0]->allow_view = $allList;
            $permissionData[0]->allow_add = $allList;
            $permissionData[0]->allow_edit = $allList;
            $permissionData[0]->allow_delete = $allList;
            $permissionData[0]->allow_search = $allList;
            $permissionData[0]->allow_email = $allList;
        }
        return $permissionData;
    }
    
}