<?php

namespace App\Http\Components;
use App\Models\User;
use App\Models\Author;
use App\Models\AuthorFiles;
use DB;
use Exception;
use Illuminate\Support\Arr;

class AuthorComponent
{
    /**
     * Used for save the author detail
     *
     * @param $request
     * @param int $authorId
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function saveAuthor($request, $authorId = null)
    {
        $result = DB::transaction(function() use($request, $authorId)
        {
            try{
                if (isset($authorId) && !empty($authorId)) {
                    $author = Author::find($authorId);
                    $returnData = [
                        'status' => 'success',
                        'msg' => trans('message.author_updated_successfully'),
                    ];
                } else {
                    $author = new Author();
                    $author->status = 'active';
                    $returnData = [
                        'status' => 'success',
                        'msg' => trans('message.author_added_successfully'),
                    ];
                }
                $author->first_name = $request->first_name;
                $author->last_name = $request->last_name;
                $author->email = $request->email;
                $author->birthdate = $request->birthdate;
                $author->type = $request->type;
                $author->country = $request->country;
                $result = $author->save();
                if ($result) {
                    $authorId = $author->id;
                    if($request->has('attachedFiles')) {
                        $this->saveAuthorFiles($request, $authorId); //save warehouse cost images detail
                    }
                } else {
                    $returnData = [
                        'status' => 'error',
                        'msg' => trans('message.something_went_wrong'),
                    ];
                }
                return $returnData;
            }catch(\Exception $ex){
                $returnData['status'] = 'error';
                $returnData['msg'] = $ex->getMessage();
            }
        });
        return $result;
    }
    /**
     * Used to get the author list
     *
     * @param $searchArray[]
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]|Collections[]
     */
    public function getDataList($searchArray = null)
    {
        $search = isset($searchArray['searchText']) ? $searchArray['searchText'] : '';
        $aistatus = isset($searchArray['aistatus']) ? $searchArray['aistatus'] : '';
        $isForCSV = isset($searchArray['isForCSV']) ? $searchArray['isForCSV'] : false;
        $columnToSort = isset($searchArray['columnToSort']) ? $searchArray['columnToSort'] : null;
        $sortType = isset($searchArray['sortType']) ? $searchArray['sortType'] : null;
        $offset = isset($searchArray['offset']) ? $searchArray['offset'] : null;
        $limit = isset($searchArray['limit']) ? $searchArray['limit'] : null;
        $usertype = isset($searchArray['usertype']) ? $searchArray['usertype'] : null;
        $startDate = isset($searchArray['startDate']) ? $searchArray['startDate'] : null;
        $endDate = isset($searchArray['endDate']) ? $searchArray['endDate'] : null;
        $callFor = isset($searchArray['callFor']) ? $searchArray['callFor'] : 'count';
        $request = isset($searchArray['request']) ? $searchArray['request'] : '';
        $columns = $request->columns;
        $dataList = Author::where('id', '>', 0);
        if (!empty($search)) {
            $dataList = $dataList->where(function ($dataList) use ($search) {
                return $dataList->Where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('country', 'like', '%' . $search . '%')
                    ->orWhere('birthdate', 'like', '%' . $search . '%');
            });
        }
        foreach ($columns as $kcolumn => $vcolumn) {
            if ($kcolumn == 1) {
                $firstNameSearch = $vcolumn['search']['value'];
                if (!empty($firstNameSearch)) {
                    $dataList = $dataList->where(function ($dataList) use ($firstNameSearch) {
                        return $dataList->where('first_name', 'like', '%' . $firstNameSearch . '%');
                    });
                }
            } else if ($kcolumn == 2) {

                $lastNameSearch = $vcolumn['search']['value'];
                if (!empty($lastNameSearch)) {
                    $dataList = $dataList->where(function ($dataList) use ($lastNameSearch) {
                        return $dataList->where('last_name', 'like', '%' . $lastNameSearch . '%');
                    });
                }
            } else if ($kcolumn == 3) {

                $emailSearch = $vcolumn['search']['value'];
                if (!empty($emailSearch)) {
                    $dataList = $dataList->where(function ($dataList) use ($emailSearch) {
                        return $dataList->where('email', 'like', '%' . $emailSearch . '%');
                    });
                }
            } else if ($kcolumn == 5) {

                $countrySearch = $vcolumn['search']['value'];
                if (!empty($countrySearch)) {
                    $dataList = $dataList->where(function ($dataList) use ($countrySearch) {
                        return $dataList->where('country', 'like', '%' . $countrySearch . '%');
                    });
                }
            }
        }
        if (!empty($aistatus)) {
            $dataList = $dataList->where(function ($dataList) use ($aistatus) {
                return $dataList->Where('status', $aistatus);
            });
        }

        if (!empty($startDate) && !empty($endDate)) {
            $dataList = $dataList->where(function ($dataList) use ($startDate, $endDate) {
                return $dataList->whereBetween('birthdate', [$startDate, $endDate]);
            });
        }

        if (!empty($usertype)) {
            $dataList = $dataList->where(function ($dataList) use ($usertype) {
                return $dataList->WhereIn('type', $usertype);
            });
        }
        // set the limit & offset
        if ($callFor == 'data') {
            if (!empty($limit)) {
                $dataList = $dataList->take($limit);
            }

            if (!empty($offset)) {
                $dataList = $dataList->skip($offset);
            }
        }
        $columnsOrder = [
            'id',
            'first_name',
            'last_name',
            'email',
            'birthdate',
            'country',
            'type',
            'status',
            'status',
        ];

        // column to sort
        if (!empty($columnsOrder[$columnToSort])) {

            $columnNameToSort = $columnsOrder[$columnToSort];

            if ($sortType == 'desc') {
                $dataList = $dataList->orderBy($columnNameToSort, 'DESC');
            } else {
                $dataList = $dataList->orderBy($columnNameToSort);
            }
        } else {
            $dataList = $dataList->orderBy('id', 'ASC');
        }
        if ($callFor == 'data') {
            return $dataList->get();
        } else {
            return $dataList->count();
        }
    }
     /**
     * Used for save the author images detail
     *
     * @param $request
     * @param int $authorId
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]
     */
    public function saveAuthorFiles($request, $authorId)
    {
        
        /**
         * check whether this $authorId images exits ir not,
         * if exits then remove first old records
         */
        
        $returnData = [
            'status' => 'success',
            'msg' => trans('message.author_files_saved_successfully')
        ];
        
        
        $authorFileDetail = $request->all(); //get all request data in array formate
        for($f=0; $f<count($request->attachedFiles); $f++){
            
            $authorFile = new AuthorFiles();
            
            $authorFile->author_id                   = $authorId;
            $authorFile->attached_file              = $authorFileDetail['attachedFiles'][$f]['rename'];
            $authorFile->attached_file_orignal_name = $authorFileDetail['attachedFiles'][$f]['orignal_name'];
            
            $resultauthorFile  = $authorFile->save();
            if(isset($request->is_direct_upload) && $request->is_direct_upload) {
                $resultauthorFile = $authorFile;
            }
        }
        // if saved successfully then return success msg
        if ($resultauthorFile) {
            
            return $resultauthorFile;
        }
        else {
            $returnData = [
                'status' => 'error',
                'msg' => trans('message.something_went_wrong')
            ];
            return $returnData;
        }
    }
    /**
     * Used for delete the order image
     *
     * @param int $fileId
     * @return array $returnData,that conatin the status with message
     */
    public function deleteAuthorFile($fileId)
    {
        // set the default message
        $returnData = [
            'status' => 'error',
            'msg' => trans('message.something_went_wrong')
        ];
        
        $oAttachment = AuthorFiles::find($fileId);
        // set the value for deleted_at
        if (AuthorFiles::destroy($fileId)) {
            $returnData = [
                'status' => 'success',
                'msg' => trans('message.author_file_has_been_deleted_successfully'),
                'file_name' => $oAttachment->attached_file,
            ];
        }
        
        return $returnData;
    }

    public function customAction($authorIds,$action)
    {
        $returnData = [
            'status' => 'error',
            'msg' => trans('message.something_went_wrong')
        ];
        if($action == 'delete') {
            Author::whereIn('id',$authorIds)->delete();
            $returnData = [
                'status' => 'success',
                'msg' => trans('message.items_has_been_deleted_successfully')
            ];
        } else if (in_array($action,array('active','inactive'))){
            Author::whereIn('id',$authorIds)->update(['status'=>$action]);
            $returnData = [
                'status' => 'success',
                'msg' => trans('message.status_has_been_changed_successfully')
            ];
        }
        return $returnData;
    }

    public function importAuthorCSV($request)
    {
        if ($request->hasFile('import_author_file')) {
            $validFile = true; 
            // get current time and append the upload file extension to it,
            // then put that name to $brandsLogo variable. 
            $extension = $request->import_author_file->getClientOriginalExtension();
             if(!in_array(strtolower($extension), array('csv'))){
                $validFile= false;
                $returnData = [
                                'status' => 'error',
                                'msg'    => trans('message.invalid_file_type_please_select_valid_file'),
                                'data'   => '',
                            ];
                return $returnData;
            } else {
                
                //$brandsLogoOrignalName = $request->import_author_file->getClientOriginalName();
                $import_author_file = time().'.'.$extension;
                /*
                 take the select file and move it public directory and make products
                 folder if doesn't exsit then give it that unique name.
                 */
                $filePath = storage_path().'/author_import_csv/';
                if (!file_exists($filePath)) {
                    mkdir($filePath, 0777, true);
                }
                $request->import_author_file->move(storage_path('author_import_csv'), $import_author_file);
                // Import CSV to Database
                $filepath = storage_path("author_import_csv/".$import_author_file);

                // Reading file
                $file = fopen($filepath,"r");

                $importData_arr = array();
                $i = 0;
                $returnData = [
                    'status' => 'error',
                    'msg'    => trans('message.something_went_wrong'),
                    'data'   => '',
                ];
                $suc = $fail = 0;
                while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                    $num = count($filedata);
                 
                    // Skip first row (Remove below comment if you want to skip the first row)
                    if($i == 0){
                        $i++;
                        continue; 
                    }
                    $i++;
                    if (isset($filedata[0]) && isset($filedata[1]) && isset($filedata[2]) && isset($filedata[3]) && isset($filedata[4]) && isset($filedata[5])) {
                        $main = Author::where('email',$filedata[2])->first();
                        
                        if(!$main){
                            $request->merge(['first_name' => $filedata[0]]);
                            $request->merge(['last_name' => $filedata[1]]);
                            $request->merge(['email' => $filedata[2]]);
                            $request->merge(['birthdate' => date("Y-m-d", strtotime($filedata[3]))]);
                            $request->merge(['country' => $filedata[4]]);
                            $request->merge(['type' => $filedata[5]]);
                            $returnData = $this->saveAuthor($request);
                            $suc++;
                        } else {
                            $fail++;
                        }
                    } else {
                        $fail++;
                    }
                }
                $returnData['msg'] = trans('message.import_author_successfully_msg', ['success_count' => $suc,'faild_count' => $fail]);
                return $returnData;

            }
        }else {
            $returnData = [
                        'status' => 'error',
                        'msg'    => trans('message.something_went_wrong'),
                        'data'   => '',
                    ];
        }
        return $returnData;
    }
}