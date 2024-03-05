@extends('app')
@php
$title = trans('message.add_user');
if(isset($postBackData) && isset($postBackData->id) && $postBackData->id > 0) {
    $title = trans('message.edit_user');
}
@endphp
@section('pageTitle', $title)
@section('contentTitle', $title)

@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-one-user-tab" data-toggle="pill" href="#custom-tabs-one-user" role="tab" aria-controls="custom-tabs-one-user" aria-selected="true">{{$title}}</a>
                            </li>
                            @if(@$postBackData->type != 'Admin')
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-permission-tab" data-toggle="pill" href="#custom-tabs-one-permission" role="tab" aria-controls="custom-tabs-one-permission" aria-selected="false">{{trans('message.permission')}}</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                    <div class="card-body">
                         @if(!isset($userId))
                        <form action="{{ route('userStore') }}" method="POST" id="user_form" name="user_form" autocomplete="off" enctype="multipart/form-data" class="form-horizontal">
                        @else
                        <form action="{{ url('user', Utility::encode(@$userId)) }}" name="user_form" id="user_form" class="form-validate-jquery" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="hduserId" id="hduserId" value="{{ @$userId }}">
                            @method('put')
                        @endif
                        @csrf
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-one-user" role="tabpanel" aria-labelledby="custom-tabs-one-user-tab">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label for="email" class="col-sm-2 col-form-label">{{trans('message.email')}}</label>
                                            <div class="col-sm-10">
                                                <input type="text" value="{{old('email', @$postBackData->email) }}" class="form-control" id="email" name="email" placeholder="{{trans('message.email')}}">
                                                <div id="email_validate"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-2 col-form-label">{{trans('message.name')}}</label>
                                            <div class="col-sm-10">
                                                <input type="text" value="{{old('name', @$postBackData->name) }}" class="form-control" id="name" name="name" placeholder="{{trans('message.name')}}">
                                                <div id="name_validate"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="user_type" class="col-sm-2 col-form-label">{{trans('message.user_type')}}</label>
                                            <div class="col-sm-10">
                                                <select id="type" class="form-control" name="user_type">
                                                    <option value="">{{trans('message.select_user_type')}}</option>
                                                    @php
                                                    $userType = old('user_type', @$postBackData->user_type);
                                                    for($i = 1; $i < 4; $i++){ @endphp <option value="{{$i}}" <?php echo $userType == $i ? "selected" : ""; ?>>User Type {{$i}}</option>
                                                        @php
                                                        }
                                                        @endphp
                                                </select>
                                                <div id="user_type_validate"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="gender" class="col-sm-2 col-form-label">{{trans('message.gender')}}</label>
                                            <div class="col-sm-10">
                                                <select id="gender" class="form-control" name="gender">
                                                    <option value="">{{trans('message.select_gender')}}</option>
                                                    @php
                                                    $gender = array('male','female');
                                                    $genderVal = old('gender', @$postBackData->gender);
                                                    for($i = 0; $i < count($gender); $i++){ @endphp <option value="{{$gender[$i]}}" <?php echo $genderVal == $gender[$i] ? "selected" : ""; ?>>{{ucfirst($gender[$i])}}</option>
                                                        @php
                                                        }
                                                        @endphp
                                                </select>
                                                <div id="gender_validate"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                @if(@$postBackData->type != 'Admin')
                                <div class="tab-pane fade" id="custom-tabs-one-permission" role="tabpanel" aria-labelledby="custom-tabs-one-permission-tab">
                                    @php
                                        // Create array of operation for module
                                        $operation = [
                                            'all' => trans('message.all'),
                                            'view' => trans('message.view'),
                                            'add' => trans('message.add'),
                                            'edit' => trans('message.edit'),
                                            'delete' => trans('message.delete'),
                                        ];
                                        
                                        $CheckPermissionName = [
                                            'chkPermissionAll',
                                            'chkPermissionAdd',
                                            'chkPermissionEdit',
                                            'chkPermissionDelete',
                                            'chkPermissionEmail',
                                        ];
                                    @endphp
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table permission-table table-bordered table-lg">
                                                <thead>
                                                    <tr class="bg-teal-400">
                                                        <th>{{trans('message.modules')}}</th>
                                                        @foreach ($operation as $k => $v)
                                                            <?php
                                                                $disabled = '';
                                                                $lblShow = $v;
                                                                if(isset($users->is_main) && $users->is_main == 1 && $v == trans('message.add')) {
                                                                    $disabled = "disabled='disabled'";
                                                                }
                                                            ?>
                                                            <th>
                                                                <div class="clearfix">
                                                                    <div class="icheck-primary d-inline">
                                                                        <input type="checkbox" class="styled" name="{{ 'chk'.$v }}" id="{{ 'chk'.$v }}" {{ $disabled }} >
                                                                        <label for="{{ 'chk'.$v }}">
                                                                            <strong>{{ $lblShow }}</strong>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    //user permission foreach loop
                                                    if(!empty($permissionData)){
                                                        //define variable
                                                        $moduleAllIds = $moduleViewIds = $moduleAddIds = $moduleEditIds = $moduleDeleteIds = $moduleEmailIds = '';
                                                        
                                                        foreach ($permissionData as $i => $j)
                                                        {
                                                            $moduleAllIds = $j->allow_all;
                                                            $moduleViewIds = $j->allow_view;
                                                            $moduleAddIds = $j->allow_add;
                                                            $moduleEditIds = $j->allow_edit;
                                                            $moduleDeleteIds = $j->allow_delete;
                                                            $moduleEmailIds = $j->allow_email;
                                                        }
                                                        
                                                        $moduleAllIds = explode(',',$moduleAllIds);
                                                        $moduleViewIds = explode(',',$moduleViewIds);
                                                        $moduleAddIds = explode(',',$moduleAddIds);
                                                        $moduleEditIds = explode(',', $moduleEditIds);
                                                        $moduleDeleteIds = explode(',',$moduleDeleteIds);
                                                        $moduleEmailIds = explode(',',$moduleEmailIds);
                                                    }
                                                    
                                                    //module forach loop
                                                    foreach($moduleData as $key=>$value)
                                                    {
                                                        //To make tenant option invisible to all tenant
                                                        $checkboxAttr = '';
                                                    
                                                @endphp
                                                    <tr class="main-tr" data-module="{{strtolower($value->module_name) }}">
                                                        @if($value->main_menu != 1)
                                                            <th>{{ $value->module_name }}</th>
                                                            @foreach ($operation as $k => $v)
                                                                @php 
                                                                    $currentPermission = 'module'.$v.'Ids';
                                                                @endphp
                                                            <td class="tdselect{{$value->id}}">
                                                                <div class="clearfix">
                                                                    <div class="icheck-primary d-inline">
                                                                        
                                                                        <input type="checkbox" name="{{ 'chkPermission'.$v.'[]' }}"  id="{{ 'chkPermission'.$v.$value->id }}" class="{{ 'chkPermission'.$v }} styled permission_chk_box" value="{{ Utility::encode($value->id) }}" 
                                                                            <?php 
                                                                            
                                                                            if(!empty($$currentPermission) && in_array($value->id, $$currentPermission)){ 
                                                                                echo 'checked'; 
                                                                            }
                                                                            if('chkPermission' . $v == 'chkPermissionView' && ! empty($$currentPermission) && !in_array($value->id, $$currentPermission ) )
                                                                            {
                                                                                $checkboxAttr = 'disabled';
                                                                            }
                                                                            if($checkboxAttr=='disabled' && in_array('chkPermission' . $v,$CheckPermissionName))
                                                                            {
                                                                                echo "disabled='disabled'";
                                                                            }
                                                                            if(in_array('chkPermission' . $v,$CheckPermissionName) && empty($$currentPermission))
                                                                            {
                                                                                echo "disabled='disabled'";
                                                                            }
                                                                            ?>
                                                                        > <!-- end input tage -->
                                                                        <label for="{{ 'chkPermission'.$v.$value->id }}">
                                                                            <strong></strong>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            @endforeach
														@else 	
															<th colspan="6" >{{ $value->module_name}}</th>
                                                        @endif
                                                    </tr>
                                                    @php 
                                                    } 
                                                @endphp
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="card-footer">
                                <a href="{{route('userIndex')}}" class="btn btn-info"><i class="fas fa-arrow-left"></i> {{trans('message.back')}}</a>
                                <button type="submit" class="btn btn-info float-right">{{trans('message.submit')}}</button>
                            </div>
                            <!-- /.card-footer -->
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@stop


@section('javascript')

<script type="text/javascript">
	var user_is_main = "<?php echo (isset($postBackData->is_main)) ? $postBackData->is_main : 0; ?>";
</script>
<script type="text/javascript" src="{!! url('resource', ['js','user_create']); !!}"></script>
@endsection
