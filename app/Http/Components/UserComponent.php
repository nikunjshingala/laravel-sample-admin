<?php

namespace App\Http\Components;
use App\Models\User;
use DB;
class UserComponent
{
    /**
     * Used to save the user details
     *
     * @param $request
     * @param $userId
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]|Collections[]
     */
    public function saveUser($request, $userId = null)
    {
        $result = DB::transaction(function() use($request, $userId)
        {
            if (isset($userId) && !empty($userId)) {
                $user = User::find($userId);
                $returnData = [
                    'status' => 'success',
                    'msg' => "User updated successfully"
                ];
            } else {
                $user = new User();
                $user->status = 'active';
                $returnData = [
                    'status' => 'success',
                    'msg' => "User saved successfully"
                ];
            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->user_type = $request->user_type;
            $result = $user->save();
            if ($result) {
                $returnData['last_insert_id'] = $user->id;
            } else {
                $returnData = [
                    'status' => 'error',
                    'msg' => trans('message.something_went_wrong')
                ];
            }
            return $returnData;
        });
        return $result;
    }
    /**
     * Used to get the user list
     *
     * @param Array $searchArray[]
     * @return string[]|\Illuminate\Contracts\Translation\Translator[]|array[]|NULL[]|Collections[]
     */
    public function getDataList($searchArray = null)
    {
        $search = isset($searchArray['searchText']) ? $searchArray['searchText'] : '';
        $columnToSort = isset($searchArray['columnToSort']) ? $searchArray['columnToSort'] : null;
        $sortType = isset($searchArray['sortType']) ? $searchArray['sortType'] : null;
        $offset = isset($searchArray['offset']) ? $searchArray['offset'] : null;
        $limit = isset($searchArray['limit']) ? $searchArray['limit'] : null;
        $callFor = isset($searchArray['callFor']) ? $searchArray['callFor'] : 'count';
        $request = isset($searchArray['request']) ? $searchArray['request'] : '';
        $userList = User::where('id', '>', 0)->where('status','!=','deleted');
        if (!empty($search)) {
            $userList = $userList->where(function ($userList) use ($search) {
                return $userList->Where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('gender', 'like', '%' . $search . '%');
            });
        }
        // set the limit & offset
        if ($callFor == 'data') {
            if (!empty($limit)) {
                $userList = $userList->take($limit);
            }

            if (!empty($offset)) {
                $userList = $userList->skip($offset);
            }
        }
        $columnsOrder = [
            'name',
            'email',
            'gender',
            'user_type',
            'status',
        ];

        // column to sort
        if (!empty($columnsOrder[$columnToSort])) {

            $columnNameToSort = $columnsOrder[$columnToSort];

            if ($sortType == 'desc') {
                $userList = $userList->orderBy($columnNameToSort, 'DESC');
            } else {
                $userList = $userList->orderBy($columnNameToSort);
            }
        } else {
            $userList = $userList->orderBy('id', 'ASC');
        }
        if ($callFor == 'data') {
            return $userList->get();
        } else {
            return $userList->count();
        }
    }
}