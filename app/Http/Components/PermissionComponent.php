<?php

namespace App\Http\Components;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Menus;
use App\Models\Permission;
use App\Http\Helpers\Utility;
use Auth;
use DB;
class PermissionComponent
{
    /**
     * used for getting menu permission data
     *
     * @param int $userId
     *
     * @return array $result
     */
    public function getUserPermission($userId='')
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
    
    /**
     * Used for get menu module data
     *
     * @return Array $result
     */
    public function getMenuModal()
    {
        $menuData = Menus::select("*")
                                    ->where('status', 1)
                                    ->where('e_type', 'Secure')
                                    ->orderBy('module_order','ASC')
                                    //->orderBy('main_menu','DESC')
                                    ->get();
        
        return $menuData;
    }
    
    /**
     * Used for get user data
     *
     * @return Array $result
     */
    public function getUserList($id="")
    {
        
        $userData = User::select("*")
                        ->where('id','!=', Auth::user()->id)
                        ->where('status','active')
                        ->when($id, function($q) use($id) {
                                $q->orWhere('id', $id);
                        })
                        ->get();
        
        return $userData;
        
    }

    /**
     * Used to save the user permission
     *
     * @param $request
     * @param $userId
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]|Collections[]
     */
    public function savePermission($request,$userId) 
    {
        $result = DB::transaction(function() use($request, $userId)
        {
            // check menu id for view permission is not empty than decode them else give blank
            $result = Permission::where(['user_id' => $userId, 'status'=> 'Active'])->update(['status'=> 'deleted']);

            if (! empty($request->chkPermissionAll)) {
                // Decode menu id for All permission
                foreach ($request->chkPermissionAll as $menuAllId) {
                    $allowAllId [] = Utility::decode($menuAllId);
                }
                $allowAll = implode(',', $allowAllId);
            }
            else {
                $allowAll = '';
            }

            // check menu id for view permission is not empty than decode them else give blank
            if (! empty($request->chkPermissionView)) {
                // Decode menu id for view permission
                foreach ($request->chkPermissionView as $menuViewId) {
                    $allowViewId [] = Utility::decode($menuViewId);
                }
                $allowView = implode(',', $allowViewId);
            }
            else {
                $allowView = '';
            }
            
            // check menu id for add permission is not empty than decode them else give blank
            if (! empty($request->chkPermissionAdd)) {
                // Decode menu id for add permission
                foreach ($request->chkPermissionAdd as $menuAddId) {
                    $allowAddId [] = Utility::decode($menuAddId);
                }
                $allowAdd = implode(',', $allowAddId);
            }
            else {
                $allowAdd = '';
            }
            
            // check menu id for edit permission is not empty than decode them else give blank
            if (! empty($request->chkPermissionEdit)) {
                // Decode menu id for edit permission
                foreach ($request->chkPermissionEdit as $menuEditId) {
                    $allowEditId [] = Utility::decode($menuEditId);
                }
                $allowEdit = implode(',', $allowEditId);
            }
            else {
                $allowEdit = '';
            }
            
            // check menu id for delete permission is not empty than decode them else give blank
            if (! empty($request->chkPermissionDelete)) {
                // Decode menu id for delete permission
                foreach ($request->chkPermissionDelete as $menuDeleteId) {
                    $allowDeleteId [] = Utility::decode($menuDeleteId);
                }
                $allowDelete = implode(',', $allowDeleteId);
            }
            else {
                $allowDelete = '';
            }
            
            // check menu id for email permission is not empty than decode them else give blank
            if (! empty($request->chkPermissionEmail)) {
                // Decode menu id for Email permission
                foreach ($request->chkPermissionEmail as $menuEmailId) {
                    $allowEmailId [] = Utility::decode($menuEmailId);
                }
                $allowEmail= implode(',', $allowEmailId);
            }
            else {
                $allowEmail= '';
            }

            $permission = new Permission();
            $permission->user_id        = $userId;
            $permission->allow_all      = $allowAll;
            $permission->allow_view     = $allowView;
            $permission->allow_add      = $allowAdd;
            $permission->allow_edit     = $allowEdit;
            $permission->allow_delete   = $allowDelete;
            
            $result = $permission->save();
            return $result;
        });
        return $result;
    }
    
    /**
     * Used to save the user permission
     *
     * @param $moduleId
     * @param $userId
     * @param $userRole
     * @param $className
     * @param $action
     * @param $return
     * @param $groupId
     * @return boolean 
     */
    public function checkPermission($moduleId, $userId, $userRole, $className = '' ,$action = '', $return = false, $groupId = false)
    {
        
        //dd(\Route::currentRouteAction());
        
        $groupId = $groupId === false ? $userId : $groupId;
        
        if ($userRole == "Admin"){
            return true;
        }
        
        if ($userRole == "Employee") {
            
            $whiteList = [
                'LoginController'           => ['index', 'login', 'logout'],
                'ResetPasswordController'   => ['index', 'reset', 'showResetForm', 'broker'],
                'ForgotPasswordController'  => ['index', 'sendResetLinkEmail', 'showLinkRequestForm', 'broker'],
                'error'                     => ['index','permission'],
            ];
            
            
            if (empty($action)) {
                $currentAction = \Route::currentRouteAction();
                list($controller, $method) = explode('@', $currentAction);
                $action = preg_replace('/.*\\\/', '', $method);
            }
            
            // Do not check for permission in whitelisted methods
            if(isset($whiteList[$className]) && in_array($action,$whiteList[$className])) {
                return true;
            }
            switch ($action) {
                case "index":
                case "show":
                    $permissionName = "view";
                    break;
                case "create":
                case "store":
                case "add":
                    $permissionName = "add";
                    break;
                case "toggleStatus":
                case "update":
                case "edit":
                    $permissionName = "edit";
                    break;
                case "searchData":
                case "filterData":
                    $permissionName = "view";
                    break;
                case "action":
                case "destroy":
                case "delete":
                    $permissionName = "delete";
                    break;
                case "sendEmail":
                case "emailSend":
                case "email":
                    $permissionName = "email";
                    break;
                default:
                    $permissionName = "view";
                    break;
            }
            
            /*
             * IF not found Records from REDIS then checks that called module has been permitted or not for
             * particular action from DATABASE
             */
            
            
            $permittedModules = Permission::select("allow_". strtolower($permissionName) . " as modules")
                                        ->where('status', 'Active')
                                        ->where('user_id', $groupId)
                                        ->get();
           
            if ($permittedModules->count() > 0) {
                $permittedModules = explode(",", $permittedModules[0]->modules);
                $resultClass = Menus::select("id")->where('controller_name', $className)->whereNotNull('controller_name')->get();
                $controllerId = '';
                if(!empty($resultClass->count()))
                {
                    $controllerId = $resultClass [0]->id;

                    if (!in_array($controllerId, $permittedModules))
                    {
                        if ($return) {
                            return false;
                        }
                        
                        return false;
                        //abort(403, 'Unauthorized action.');
                        die();
                    }
                }
                
            }else {
                if ($return) {
                    return false;
                }
                /* die("You are not able to access this function"); */
                return false;
                //abort(403, 'Unauthorized action.');
                die();
            }
            
            
            if (! in_array($moduleId, $permittedModules) && $moduleId > 0) {
                if ($return) {
                    return false;
                }
                
                /* die("You are not able to access this function"); */
                return false;
                //abort(403, 'Unauthorized action.');
                die();
            }
        }
        else {
            return false;
        }
        
        return true;
    }
}
