@extends('app')
@php 
    $title  = trans('message.add_author');
    if(isset($postBackData) && isset($postBackData->id) && $postBackData->id > 0) {
        $title =  trans('message.edit_author');
    }
@endphp
@section('pageTitle', $title)
@section('contentTitle', $title)
@section('customcss')
<link rel="stylesheet" href="{{asset('global_assets/plugins/dropzone/dropzone.css')}}" />
@endsection
@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">{{$title}}</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    @if(!isset($authorId))
                        <form action="{{ route('storeAuthor') }}" method="POST" id="author_form" name="author_form" autocomplete="off" enctype="multipart/form-data" class="form-horizontal form-validate-jquery" >
                    @else
                        <form action="{{ url('author', Utility::encode(@$authorId)) }}" name="author_form" id="author_form" class="form-validate-jquery" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="hdauthorId" id="hdauthorId" value="{{ @$authorId }}">
                        @method('put')
                    @endif
                        @csrf
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="email" class="col-sm-2 col-form-label">{{trans('message.email')}}</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{old('email', @$postBackData->email) }}" class="form-control" id="email" name="email" placeholder="{{trans('message.email')}}">
                                    <div id="email_validate"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="first_name" class="col-sm-2 col-form-label">{{trans('message.first_name')}}</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{old('first_name', @$postBackData->first_name) }}" class="form-control" id="first_name" name="first_name" placeholder="{{trans('message.first_name')}}">
                                    <div id="first_name_validate"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="last_name" class="col-sm-2 col-form-label">{{trans('message.last_name')}}</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{old('last_name', @$postBackData->last_name) }}" class="form-control" id="last_name" name="last_name" placeholder="{{trans('message.last_name')}}">
                                    <div id="last_name_validate"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="birthdate" class="col-sm-2 col-form-label">{{trans('message.birth_date')}}</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{old('birthdate', @$postBackData->birthdate) }}" class="form-control" id="birthdate" name="birthdate" placeholder="{{trans('message.birth_date')}}">
                                    <div id="birthdate_validate"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="type" class="col-sm-2 col-form-label">{{trans('message.user_type')}}</label>
                                <div class="col-sm-10">
                                    <select id="type" class="form-control"  name="type">
                                        <option value="">{{trans('message.select_user_type')}}</option>
                                        @php
                                            $userType = old('type', @$postBackData->type);
                                            for($i = 1; $i < 4; $i++){
                                        @endphp
                                            <option value="{{$i}}" <?php echo $userType == $i ? "selected" : ""; ?>>User Type {{$i}}</option>
                                        @php
                                            }
                                        @endphp
                                    </select>
                                    <div id="type_validate"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="country" class="col-sm-2 col-form-label">{{trans('message.country')}}</label>
                                <div class="col-sm-10">
                                    <select id="country" class="form-control"  name="country">
                                        <option value="">{{trans('message.select_country')}}</option>
                                        @php
                                            $userType = old('country', @$postBackData->country);
                                            for($i = 0; $i < count($countryList); $i++){
                                        @endphp
                                            <option value="{{$countryList[$i]}}" <?php echo $userType == $countryList[$i] ? "selected" : ""; ?>>{{$countryList[$i]}}</option>
                                        @php
                                            }
                                        @endphp
                                    </select>
                                    <div id="country_validate"></div>
                                </div>
                            </div>
                            <fieldset>
                                <label class="text-semibold">{{trans('message.upload_files')}}</label>
                                <div class="col-sm-12 p-0">
                                    <div class="dropzone" id="myDropzone">
                                        <div class="dz-message needsclick">
                                            <div>
                                            <img src="{{ asset('images/drag_drop.png') }}" width="50" height="43">
                                            </div>
                                           <label for="file">
                                                <strong>{{trans('message.choose_a_file')}}</strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group m-0">
                                    {!!trans('message.upload_files_note')!!}
                                    </div>
                                    @if(isset($postBackData->authorFiles) && count(@$postBackData->authorFiles) > 0)
                                        <div class="media-center mt-10">
                                            <div>Attached Files :</div>
                                            @foreach(@$postBackData->authorFiles as $authorFile)
                                                <span style="background-color: #e9e6e6; position: relative; display: inline-block; vertical-align: top; border: 1px solid #ddd; padding: 5px; border-radius: 2px; margin:5px" id="fileparent{{ Utility::encode($authorFile->id) }}" data-url="{{ url('download-author-attachment/'.Utility::encode($authorFile->attached_file).'/'.Utility::encode($authorFile->attached_file_orignal_name))}}">
                                                    <a style="color:#212529;" href="{{ url('download-author-attachment/'.Utility::encode($authorFile->attached_file).'/'.Utility::encode($authorFile->attached_file_orignal_name))}}" target="_blank">
                                                        {{ $authorFile->attached_file_orignal_name }}
                                                    </a>
                                                    <i class="fas fa-times-circle removeFile" data-id="{{ Utility::encode($authorFile->id) }}" data-url="{{ url('download-author-attachment/'.Utility::encode($authorFile->attached_file).'/'.Utility::encode($authorFile->attached_file_orignal_name))}}" title="close" style="cursor:pointer;"></i>
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="dropzone-previews div-table" style="margin-top:10px;"></div>
                                    <div id="preview-template" style="display: none;">
                                        <div class="dz-preview dz-file-preview div-tr">
                                            <div class="dz-details" style="float: left;">
                                                <div class="dz-filename div-td"><span data-dz-name></span></div>
                                            </div>
                                            <div class="dz-progress div-td"><span class="dz-upload" data-dz-uploadprogress></span></div>
                                            <div class="dz-error-message div-td"><span data-dz-errormessage></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div>&nbsp;</div>
                                <div>&nbsp;</div>
                            </fieldset>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <a href="{{route('authorIndex')}}" class="btn btn-info"><i class="fas fa-arrow-left"></i> {{trans('message.back')}}</a>
                            <button type="submit" class="btn btn-info float-right">{{trans('message.submit')}}</button>
                        </div>
                        <!-- /.card-footer -->
                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@stop

@section('javascript')
<script type="text/javascript" src="{{asset('global_assets/plugins/dropzone/dropzone.js')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="{!! url('resource', ['js','author_create']); !!}"></script>
@endsection