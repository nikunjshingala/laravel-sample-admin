<?php

namespace App\Http\Middleware;
use App\Http\Components\PermissionComponent;
use Closure;
use Auth;
use App\Http\Helpers\Utility;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            $access = false;
            
            $this->permissionComponent = new PermissionComponent();
            @include app_path('Http/Helpers/menu_list.php');
            $this->currentMenuId = 0;
            
            //get route info
            //dump(\Route::currentRouteAction());
            
            //get current controller name
            $currentAction = $request->route()->getActionName();
            list($controller, $method) = explode('@', $currentAction);
            $controller = preg_replace('/.*\\\/', '', $controller);
            
            foreach ($menu as $k => $v) {
                if(!empty($v['menus'])){
                    foreach ($v ['menus'] as $key => $val) {
                        if($val['menuURL'] == $controller){
                            $this->currentMenuId = $key;
                            break;
                        }
                    }
                } else {
                    if($v['menuURL'] == $controller){
                        $this->currentMenuId = $k;
                        break;
                    }
                }
            }
            $GLOBALS ["permissionMenu"] =  $this->currentMenuId;
            $access = $this->permissionComponent->checkPermission($this->currentMenuId, Auth::user()->id, Auth::user()->type,$controller);
            // access is false redirect
            if (! $access) {
                if ($request->ajax()) {
                    //set_status_header("403");
                    
                    $status_code = 0;
                    $message = trans('message.sorry_you_are_not_authorized_to_access_this_page');
                    $dataSend = [];
                    
                    return Utility::jsonOut($status_code, $message, $dataSend, '403');
                    //return response()->json(array( 'status_code' => 0, 'message' => $message ), 403);
                    exit();
                }
                
                $errorMsg['error_code'] = '403';
                $errorMsg['error_msg'] = trans('message.sorry_you_are_not_authorized_to_access_this_page');
                return redirect('error')->with($errorMsg);
            }
            
            $accessMethod = [];
            $methodArray = ['view', 'add','edit','delete', 'email'];
            foreach($methodArray as $method){
                $accessMethod[$method] = $this->permissionComponent->checkPermission($GLOBALS ["permissionMenu"], Auth::user()->id, Auth::user()->type,$controller, $method ,true);
            }
            $menuPermission = $this->permissionComponent->getUserPermission(Auth::user()->id);
            
            if(!empty($menuPermission)){
                $request->merge(['menuPermission' => $menuPermission]);
            }
            
            /*  
             * 
             * Get All action permission based oh his module
             * And merge/append in request collection named 'accessMethod'
             * so we can get globaly in system and show/hide add/edit/delete/email option 
             * 
             */
            $request->merge(['accessMethod' => $accessMethod]);
            
        }else{
            return redirect('/login');
        }
        return $next($request);
    }
}
