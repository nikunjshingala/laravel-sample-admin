<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Author;
use App\Http\Helpers\Utility;
use App\Http\Components\AuthorComponent;
use DB;
class AuthorController extends Controller
{
    public function __construct()
    {
        $this->authorComponent = new AuthorComponent;
    }

    /**
     * Used to show the listing page
     *
     * @return view
     */
    public function index()
    {
        return view('author.index');
    }

    /**
     * Used to show the create author page
     *
     * @return view
     */
    public function create()
    {
        $countryList = Author::select(DB::raw('DISTINCT country as country'))->get();
        $countryList = $countryList->pluck('country');
        return view('author.create',compact('countryList'));
    }
    /**
     * Used for store the author detail
     *
     * @param Request $request
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function store(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
            'type' => 'required',
            'country' => 'required',
            'birthdate' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
        $attachedFileArr = [];
        $validFile = true;
        $validFileType = ['jpg','jpeg','png','bmp','gif','pdf','docx','doc','txt','xls','xlsx','csv','ods'];
        $validFileSize = config('constants.MAX_UPLOAD_SIZE'); //size in KB
        if($request->has('attached_file')){
            $files = $request->attached_file;
            $f=0;
            foreach ($files as $file) {
                $file = json_decode($file);
                
                if(!in_array(strtolower($file->response[2]), $validFileType)){
                    $validFile= false;
                    $returnData['msg'] =  trans('message.invalid_file_type_please_select_valid_file');
                }
                
                if($file->response[3] > $validFileSize){
                    $validFile= false;
                    $returnData['msg'] =  trans('message.invalid_file_size');
                }
                
                $attachedFileArr[$f]['orignal_name'] = $file->response[1];
                $attachedFileArr[$f]['rename'] = $file->response[0];
                $f++;
            }
            $request->merge(['attachedFiles' => $attachedFileArr]);
        }
        if($validFile == true){
            $returnData = $this->authorComponent->saveAuthor($request);
            if ($returnData['status'] == 'success') {
                return redirect('author')->with($returnData);
            }
        }
        return redirect()->back()->withInput()->with($returnData);
        
    }
    /**
     * Used to show the edit author details page
     *
     * @param int $id
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function edit($id)
    {
        $authorId = Utility::decode($id);
        $postBackData = Author::find($authorId);

        $countryList = Author::select(DB::raw('DISTINCT country as country'))->get();
        $countryList = $countryList->pluck('country');
        return view('author.create', compact('postBackData','authorId','countryList'));
    }
    /**
     * Used to update the author details
     *
     * @param Request $request
     * @param int $id
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function update(Request $request,$id)
    {
        $rules = [
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
            'type' => 'required',
            'country' => 'required',
            'birthdate' => 'required',
            'hdauthorId' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
        $authorId = Utility::decode($id);
        $returnData = $this->authorComponent->saveAuthor($request,$authorId);
        if ($returnData['status'] == 'success') {
            return redirect('author')->with($returnData);
        } else {
            return redirect()->back()->withInput()->with($returnData);
        }
    }
    /**
     * Used to chagne the author status
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
        $author = Author::find($dataId);
        $author->status = $status;
        
        $result = $author->save();
         
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
     * Used for delete the author
     *
     * @param Request $request
     * @param int $id
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function destroy(Request $request,$id)
    {
        // Tenant Login From Central Side, Restric Add, Edit, Delete
        $returnData = [
            'status' => 'error',
            'msg' => trans('message.something_went_wrong'),
        ];
        
        $authorId = Utility::decode($id);
        // check numeric id is numeric
        if (is_numeric($authorId)) {
            $response = '';
            // set the default message
            $returnData = [
                'status' => 'error',
                'msg' => trans('message.something_went_wrong')
            ];
            
            // set the value for deleted_at
            $author = Author::find($authorId);
            
            if ($author->count() > 0) {

                Author::find($authorId)->delete();
                // success saved then return success message
                $returnData = [
                    'status' => 'success',
                    'msg' => trans('message.author_has_been_deleted_successfully')
                ];
            }
            return redirect('author')->with($returnData);
        }
        
        return redirect('author')->with($returnData);
    }
    /**
     * Used to get the author list
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
        $totalData = $this->authorComponent->getdataList($searchArray);
        $searchArray['callFor'] = 'data';
        $searchArray['offset'] = $offset;
        $searchArray['limit'] = $limit;
        $dataDetails = $this->authorComponent->getdataList($searchArray);
        $totalFiltered = $totalData;
        $data = [];
        if (count($dataDetails) > 0) {
            $i = 1;
            foreach ($dataDetails as $dataDetail) {
                if ($dataDetail->type == 1) {
                    $type = 'UserType1';
                } else if ($dataDetail->type == 2) {
                    $type = 'UserType2';
                } else if ($dataDetail->type == 3) {
                    $type = 'UserType3';
                } else {
                    $type = 'UserType4';
                }
                if ($dataDetail->status == 'active') {
                    $statusIcon = 'fas fa-eye-slash';
                    $statusTitle = trans('message.inactive');
                    $statusClass = 'inactive';
                } else {
                    $statusIcon = 'fas fa-eye';
                    $statusTitle = trans('message.active');
                    $statusClass = 'active';
                }
                if($dataDetail->status != 'deleted' && (!empty($request->accessMethod['edit']) || !empty($request->accessMethod['delete']))) {
                    $action = '<ul class="icons-list action-dropdown">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle action-dropdown-section" data-toggle="dropdown">
                                <i class="fas fa-bars"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right ">';
                            if(!empty($request->accessMethod['edit'])){
                                $action .= '<li class="action-section"><a href="' . URL('author/' . Utility::encode($dataDetail->id) . '/edit') . '" class="action-text"><i class="fas fa-edit"></i>'.trans('message.edit').'</a></li>';
                                $action .= '<li class="action-section"><a href="javascript:void(0);" data-id="' . Utility::encode($dataDetail->id) . '" class="action-text actionStatus ' . $statusClass . ' "><i class="' . $statusIcon . '"></i>' . $statusTitle . '</a></li>';
                            }
                            if(!empty($request->accessMethod['delete'])){
                                $action .= '<li class="action-section">
                                        <form action="'. URL('author', Utility::encode($dataDetail->id)).'" class="deleteAction'.$dataDetail->id.'" method="post">
                                            <input type="hidden" name="_method" value="delete">
                                            <input type="hidden" name="_token" value="'.$request->session()->token().'">
                                            <a href="javascript:void(0);" data-id="'.$dataDetail->id.'" class="deleteAction action-text"><i class="fas fa-trash"></i> '.trans('message.delete').'</a>
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
                if($dataDetail->status != 'deleted' && (!empty($request->accessMethod['edit']))) {
                    $isChecked = '';
                    if($dataDetail->status == 'active') {
                        $isChecked = 'checked';
                    }
                    $switch = '<div class="text-center">
                        <div class="custom-control custom-switch custom-switch-new custom-switch-on-success">
                            <input type="checkbox" class="custom-control-input status-switch" id="statusSwitch'.$dataDetail->id.'" data-id="' . Utility::encode($dataDetail->id) . '" '.$isChecked.'>
                            <label class="custom-control-label" for="statusSwitch'.$dataDetail->id.'"></label>
                        </div>
                    </div>';
                } else {
                    $switch = '<div class="text-center">-</div>';
                }
                $chkbox = '<div class="text-center clearfix">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="author_item_'.$dataDetail->id.'" class="author_item" name="author_item[]" value="'.$dataDetail->id.'">
                        <label for="author_item_'.$dataDetail->id.'"></label>
                    </div>
                </div>';
                $rows = [];
                $rows[] = $chkbox; 
                $rows[] = $dataDetail->first_name;
                $rows[] = $dataDetail->last_name;
                $rows[] = $dataDetail->email;
                $rows[] = $dataDetail->birthdate;
                $rows[] = $dataDetail->country;
                $rows[] = $type;
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
     * Move File file to storage.
     *
     * @param  Object  Request
     * @return \Illuminate\Http\Response
     */
    public function moveFiles(Request $request)
    {
        $file = $request->file('attached_file');
        $responseArr['status'] = false;
        $responseArr['response'] = '';
        
        if ($request->hasFile('attached_file')) {
            
            // get current time and append the upload file extension to it,
            // then put that name to $brandsLogo variable.
            
            //$file->store('users/' . $this->user->id . '/messages');
            //$brandsLogoOrignalName = $request->brands_logo->getClientOriginalName();
            $attachedFileOrignalName    = $file->getClientOriginalName();
            $attachedFileType           = $file->getClientOriginalExtension();
            $attachedFileSize           = round($file->getSize() * 0.001); //convert bytes to kb;
            $attachedFile= mt_rand().time().'.'.$file->getClientOriginalExtension();
            
            /*
             take the select file and move it public directory and make brands
             folder if doesn't exsit then give it that unique name.
             */
            $file->move(storage_path('author_files/'), $attachedFile);
            $attId = '';
            if(isset($request->authorId) && $request->authorId > 0){
                
                $attachedFileArr[0]['rename'] = $attachedFile;
                $attachedFileArr[0]['orignal_name'] = $attachedFileOrignalName;
                $request->merge(['is_direct_upload' => true]);
                $request->merge(['attachedFiles' => $attachedFileArr]);
                $attReturnArray = $this->authorComponent->saveAuthorFiles($request, $request->authorId);    //save warehouse cost images detail;
                $attId = Utility::encode($attReturnArray->id);

            }
            $responseArr['status'] = true;
            $responseArr['response'] = array($attachedFile, $attachedFileOrignalName, $attachedFileType, $attachedFileSize);
            $responseArr['id'] = $attId;
            echo json_encode($responseArr);
            exit();
            
        }
        
    }

    /**
     * Remove file from storage.
     *
     * @param  Object  Request
     * @return \Illuminate\Http\Response
     */
    public function unlinkFile(Request $request)
    {
        $responseArr['status'] = false;
        
        $file = $request->file_name;
        if(!empty($file)){
            $file_path = storage_path('author_files').'/'.$file;
            if(is_file($file_path)) {
                if(unlink($file_path)){
                    $responseArr['status'] = true;
                }
            }
        }
        echo json_encode($responseArr);
        exit();
    }

    
    /**
     * download file from storage.
     *
     * @param  Object  Request
     * @return Download file
     */
    public function downloadAttachment(Request $request)
    {
        $file_name = isset($request->file_name) ? Utility::decode($request->file_name) : '';
        $original_name = isset($request->original_name) ? Utility::decode($request->original_name) : '';
        $downloadFile = false;
        if(!empty($file_name)) {
            $file_path = storage_path('author_files'.'/'.$file_name);
            if(is_file($file_path)) {
                $downloadFile = true;
            }
        }
        if($downloadFile == true){
            return response()->download($file_path,$original_name);
        }else {
            die('No File Found.');
        }
    }
    /**
     * Remove warehouse images from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeFile(Request $request)
    {        
        $fileId = Utility::decode($request->fileId);
        // check numeric id is numeric
        if (is_numeric($fileId)) {
            $response = $this->authorComponent->deleteAuthorFile($fileId);
            
            $message = $response;
            $dataSend = [];
            $status_code = 200;
            if(isset($response['file_name']) && !empty($response['file_name'])) {
                $request->merge(['file_name' =>$response['file_name']]);
                $this->unlinkFile($request);
            }
            if($request->ajax()){
                return Utility::jsonOut($status_code, $message, $dataSend);
            }else{
                return redirect('author')->with($response);
            }
        } else {
            $statusDetail = [
                'status' => 'error',
                'msg' => trans('message.something_went_wrong')
            ];
        }
        if($request->ajax()){
            return Utility::jsonOut($statusDetail['status'], $statusDetail['msg'], []);
        } else {
            return redirect()->back()->with($statusDetail);
        }
    }
    public function customAction(Request $request)
    {
        $authorIds = isset($request->authorIds) && is_array($request->authorIds) ? $request->authorIds : array();
        $action = isset($request->action) ? $request->action : '';
        if(count($authorIds) > 0) {
            if(in_array($action,array('delete','active','inactive'))) {
                $actionDetail = $this->authorComponent->customAction($authorIds,$action);
                return response()->json($actionDetail);
            } else{
                $actionDetail = [
                    'status' => 'error',
                    'msg' => trans('message.please_select_valid_action'),
                ];
                return response()->json($actionDetail);
            }
        } else {
            $actionDetail = [
                'status' => 'error',
                'msg' => trans('message.please_select_valid_item'),
            ];
            return response()->json($actionDetail);
        }
    }
    /**
     * @OA\Get(
     *     path="/get-author",
     *     tags={"Author"},
     *     summary="Get the author list",
     *     operationId="getauthor",
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
    public function getAuthorList(Request $request)
    {
        $authorList = Author::get();
        return response()->json($authorList);
    }

    public function downloaDummyCSV()
    {        
        $csvPath = storage_path().'/author_list_example.csv';
        return response()->download($csvPath);
    }

    public function importAuthorCSV(Request $request)
    {
        $return_data = $this->authorComponent->importAuthorCSV($request);
        return $return_data;
    }

    public function authorEmailCheck(Request $request)
    {
        $id = $request->id;
        $main = Author::where('email',$request->email)->when($id, function ($q) use ($id) {
            $q->where('id','!=' , $id);
        })->first();
        if ($main) {
            echo "false";
        } else {
            echo "true";
        }
    }
}
