<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Menus;
use App\Models\Permission;
use App\Http\Helpers\Utility;
use App\Http\Components\PermissionComponent;
use App\Http\Components\UserComponent;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\Http\Controllers\LoginController;
class UserController extends Controller
{
    public function __construct() {
        $this->permissionComponent = new PermissionComponent;
        $this->userComponent = new UserComponent;
    }
    /**
     * Used to get the listing page
     *
     * @param Request $request
     * @return view
     */
    public function index(Request $request)
    {
        return view('user.index');
    }
    /**
     * Used to show the create user page
     *
     * @param Request $request
     * @return view
     */
    public function create(Request $request)
    {

        // if group select then selecte permission detail according to selected group
        $permissionData = $this->permissionComponent->getUserPermission();
        // used to get group name
        $tableRow = $this->permissionComponent->getUserList();
        // used to get modules
        $moduleData = $this->permissionComponent->getMenuModal();
        return view('user.create',compact('permissionData','tableRow','moduleData'));
    }
    /**
     * Used for store the user detail
     *
     * @param Request $request
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function store(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'name' => 'required',
            'gender' => 'required',
            'user_type' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
        $returnData = $this->userComponent->saveUser($request);
        if ($returnData['status'] == 'success') {
            $userId = $returnData['last_insert_id'];
            $email = $request->email;
            $name = $request->name;
            $emaildec = Utility::encode($email);

            $emaillink = url('account-setup/?r='.$emaildec);  

            //send success email notfication
            $data = ['emaillink' => $emaillink,'email'=>$email,'name'=>$name];

            Mail::send('account_setup', $data, function($message) use($email) {
                $message->to($email, config('constants.MAIL_FROM_NAME'))->subject
                    ("Set Account Password");
                $message->from(config('constants.MAIL_FROM_ADDRESS'), config('constants.SITE_NAME'));
            });

            $this->permissionComponent->savePermission($request,$userId);
            return redirect('user')->with($returnData);
        } else {
            return redirect()->back()->withInput()->with($returnData);
        }
    }
    /**
     * Used to show the edit user page
     *
     * @param Request $request
     * @return view
     */
    public function edit($id,Request $request)
    {
        $userId = Utility::decode($id);
        $postBackData = User::find($userId);
        // if group select then selecte permission detail according to selected group
        $permissionData = $this->permissionComponent->getUserPermission($userId);
        // used to get group name
        $tableRow = $this->permissionComponent->getUserList();
        // used to get modules
        $moduleData = $this->permissionComponent->getMenuModal();

        return view('user.create', compact('postBackData','userId','permissionData','tableRow','moduleData'));
    }
    /**
     * Used to update the user details
     *
     * @param Request $request
     * @param Int $id
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function update(Request $request,$id)
    {
        $rules = [
            'email' => 'required|email',
            'name' => 'required',
            'gender' => 'required',
            'user_type' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
        $userId = Utility::decode($id);
        $returnData = $this->userComponent->saveUser($request,$userId);
        if ($returnData['status'] == 'success') {
            $this->permissionComponent->savePermission($request,$userId);
            return redirect('user')->with($returnData);
        } else {
            return redirect()->back()->withInput()->with($returnData);
        }
    }
    /**
     * Used to update the user status
     *
     * @param Request $request
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function toggleStatus(Request $request)
    {
         // set the default responce
         $status_code = 0;
         $message = trans('message.something_went_wrong');
         $dataSend = [];
         
         // check if id is not numeric
         if (! is_numeric(Utility::decode($request->DataId))) {
             return Utility::jsonOut($status_code, $message = '', $dataSend);
         }
         
         $dataId = Utility::decode($request->DataId);
         $doStatus = $request->doStatus;
         
         
        $status = $doStatus == 'Active' ? 'active' : 'inactive';
        $user = User::find($dataId);
        $user->status = $status;
        
        $result = $user->save();
         
         if ($result !== false) {
            $status_code = 1;
            if ($doStatus == 'Active')
                $message = trans('message.record_has_been_activated_successfully');
            else
                $message = trans('message.record_has_been_inactivated_successfully');
         }
         // return the responce
         return Utility::jsonOut($status_code, $message, $dataSend);
    }
    /**
     * Used to delete the user
     *
     * @param Request $request
     * @param Int $id
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function destroy(Request $request,$id)
    {
        // Tenant Login From Central Side, Restric Add, Edit, Delete
        $returnData = [
            'status' => 'error',
            'msg' => trans('message.something_went_wrong'),
        ];
        
        $userId = Utility::decode($id);
        // check numeric id is numeric
        if (is_numeric($userId)) {
            $response = '';
            // set the default message
            $returnData = [
                'status' => 'error',
                'msg' => trans('message.something_went_wrong')
            ];
            
            // set the value for deleted_at
            $user = User::find($userId);
            
            if ($user->count() > 0) {

                $user->status = 'deleted';
                // success saved then return success message
                if ($user->save()) {
                    $returnData = [
                        'status' => 'success',
                        'msg' => trans('message.user_has_been_deleted_successfully')
                    ];
                }
            }
            return redirect('user')->with($returnData);
        }
        
        return redirect('user')->with($returnData);
    }
    /**
     * Used to get the list of the user
     *
     * @param Request $request
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function filterData(Request $request)
    {

        $searchData = $request->search;
        $aistatus = $request->aistatus;
        $offset = $request->start;
        $limit = !empty($request->length) ? $request->length : 25;
        $searchArray = array();
        $searchText = $searchData['value'];
        $searchArray['searchText'] = $searchText;
        $searchArray['aistatus'] = $aistatus;
        $searchArray['startDate'] = isset($request->startDate) ? $request->startDate : '';
        $searchArray['endDate'] = isset($request->endDate) ? $request->endDate : '';
        $searchArray['usertype'] = isset($request->usertype) ? $request->usertype : '';
        $searchArray['request'] = $request;

        $columnToSortData = isset($request->order) ? $request->order : '';
        $columnToSort = $sortType = '';
        if (isset($columnToSortData[0])) {
            $columnToSort = $columnToSortData[0]['column'];
            $sortType = $columnToSortData[0]['dir'];
        }
        $searchArray['columnToSort'] = $columnToSort;
        $searchArray['sortType'] = $sortType;
        $totalData = $this->userComponent->getDataList($searchArray);
        $searchArray['callFor'] = 'data';
        $searchArray['offset'] = $offset;
        $searchArray['limit'] = $limit;
        $dataDetails = $this->userComponent->getDataList($searchArray);
        $totalFiltered = $totalData;
        $data = [];
        if (count($dataDetails) > 0) {
            $i = 1;
            foreach ($dataDetails as $userDetail) {
                if ($userDetail->user_type == 1) {
                    $user_type = 'UserType1';
                } else if ($userDetail->user_type == 2) {
                    $user_type = 'UserType2';
                } else if ($userDetail->user_type == 3) {
                    $user_type = 'UserType3';
                } else {
                    $user_type = 'UserType4';
                }
                if ($userDetail->status == 'active') {
                    $statusIcon = 'fas fa-eye-slash';
                    $statusTitle = 'Inactive';
                    $statusClass = 'inactive';
                } else {
                    $statusIcon = 'fas fa-eye';
                    $statusTitle = 'Active';
                    $statusClass = 'active';
                }
                if($userDetail->status != 'deleted' && (!empty($request->accessMethod['edit']) || !empty($request->accessMethod['delete']))) {
                    $action = '<ul class="icons-list action-dropdown">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle action-dropdown-section" data-toggle="dropdown">
                                <i class="fas fa-bars"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right ">';
                            if(!empty($request->accessMethod['edit'])) {
                                $action .= '<li class="action-section"><a href="' . URL('user/' . Utility::encode($userDetail->id) . '/edit') . '" class="action-text"><i class="fas fa-edit"></i>'.trans('message.edit').'</a></li>';

                                //$action .= '<li class="action-section"><a href="javascript:void(0);" data-id="' . Utility::encode($userDetail->id) . '" class="action-text actionStatus ' . $statusClass . ' "><i class="' . $statusIcon . '"></i>' . $statusTitle . '</a></li>';
                                $action .= '<li class="action-section"><a href="javascript:void(0);" data-id="' . Utility::encode($userDetail->id) . '" class="action-text reset-password"><i class="fas fa-unlock"></i>'.trans('message.send_reset_password_link').'</a></li>';
                            }
                            if(!empty($request->accessMethod['delete']) && $userDetail->id != Auth::user()->id){
                                $action .= '<li class="action-section">
                                        <form action="'. URL('user', Utility::encode($userDetail->id)).'" class="deleteAction'.$userDetail->id.'" method="post">
                                            <input type="hidden" name="_method" value="delete">
                                            <input type="hidden" name="_token" value="'.$request->session()->token().'">
                                            <a href="javascript:void(0);" data-id="'.$userDetail->id.'" class="deleteAction action-text"><i class="fas fa-trash"></i> '.trans('message.delete').'</a>
                                        </form>
                                    </li>';
                            }
                            $action .= '
                            </ul>
                        </li>
                    </ul>';
                } else {
                    $action = '<ul class="icons-list action-dropdown">
                    <li class="dropdown">-</li></ul>';
                }
                if($userDetail->status != 'deleted' && (!empty($request->accessMethod['edit']))) {
                    $isChecked = '';
                    if($userDetail->status == 'active') {
                        $isChecked = 'checked';
                    }
                    $switch = '<div class="text-center">
                        <div class="custom-control custom-switch custom-switch-new custom-switch-on-success">
                            <input type="checkbox" class="custom-control-input status-switch" id="statusSwitch'.$userDetail->id.'" data-id="' . Utility::encode($userDetail->id) . '" '.$isChecked.'>
                            <label class="custom-control-label" for="statusSwitch'.$userDetail->id.'"></label>
                        </div>
                    </div>';
                } else {
                    $switch = '<div class="text-center">-</div>';
                }
                $rows = [];
                $rows[] = $userDetail->name;
                $rows[] = $userDetail->email;
                $rows[] = ucfirst($userDetail->gender);
                $rows[] = $user_type;
                //$rows[] = ucfirst($userDetail->status);
                $rows[] = $switch;
                $rows[] = $action;

                $data[] = $rows;
                $i++;
            }

            $json_data = [
                "draw" => intval($request->draw), // for every request/draw by clientside , they send
                // a number as a parameter, when they recieve a
                // response/data they first check the draw number,
                // so we are sending same number in draw.
                "recordsTotal" => intval($totalData), // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there
                // is no searching then totalFiltered = totalData
                "data" => $data // total data array
            ];
        } else {
            $json_data = [
                "draw" => intval($request->draw), // for every request/draw by clientside , they send
                // a number as a parameter, when they recieve a
                // response/data they first check the draw number,
                // so we are sending same number in draw.
                "recordsTotal" => intval($totalData), // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there
                // is no searching then totalFiltered = totalData
                "data" => $data // total data array
            ];
        }
        return json_encode($json_data);
    }
    /**
     * Used to user account password set
     *
     * @param Request $request
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function accountSetup(Request $request)
    {
        if( $request->has('r')) {
            $r = $request->query('r');

            $email = Utility::decode($r);

            $data['email'] = $email;

            $checkPwd = User::where('email',$email)->where('status','!=','Deleted')->first();
            if (!empty($checkPwd)) {
                if ($checkPwd->password != "" || $checkPwd->password != NULL) {
                    $returnData ['status'] = 'error';
                    $returnData ['msg'] = trans('message.account_already_setup');
                    return redirect('login')->with($returnData);
                }
            }

            return view('auth.set_password')->with($data);
        }
        else
        {
            $returnData ['status'] = 'danger';
            $returnData ['msg'] = trans('message.something_went_wrong');
            
            return redirect('login')->with($returnData);
        }
    }
    /**
     * Used to rest the password
     *
     * @param Request $request
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function saveUserPassword(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->all();
            $returnData = [
                'status' => 'error',
                'msg' => $message[0]
            ];
            return redirect()->back()->withInput()->with($returnData);
        }
        $email = $request->email;
        $password = $request->password;
        if (!empty($email) && !empty($password)) {
            $updArray = ['password' => bcrypt($password)];
            $update = User::where('email',$email)->update($updArray);
            if ($update) {
                $returnData ['status'] = 'success';
                $returnData ['msg'] = trans('message.password_has_been_successfully_set');
                return redirect('login')->with($returnData);
            }
            else
            {
                $returnData ['status'] = 'error';
                $returnData ['msg'] = trans('message.password_setup_failed');
                return redirect()->back()->withInput()->with($returnData);
            }
        }
        else
        {
            $returnData ['status'] = 'error';
            $returnData ['msg'] = trans('message.something_went_wrong');
            return redirect('login')->with($returnData);
        }
    }
    public function restPasswordFromUser(Request $request)
    {
        $userId = Utility::decode($request->userId);
        $userDetail = User::find($userId);
        
        $returnData ['status'] = 'error';
        $returnData ['msg'] = trans('message.something_went_wrong');
        if(isset($userDetail) && $userDetail->count() > 0) {
            $request->merge(['email' => $userDetail->email]);
            $loginController = new LoginController;
            $returnData = $loginController->forgotPassword($request);
        }

        return $returnData;
    }
}
