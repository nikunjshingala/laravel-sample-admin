@inject('request', 'Illuminate\Http\Request')
@extends('app')

@section('pageTitle', trans('message.author_list'))
@section('contentTitle', trans('message.author_list'))

@section('customcss')
<link rel="stylesheet" href="{{asset('global_assets/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('global_assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('global_assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')

<section class="content">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                @if(!empty($request->accessMethod['add']))
                    <div class="card-header">
                        <a href="javascript:void(0)" id="import_author_anchor"><i class="fas fa-file-upload"></i> {{trans('message.import_author_csv')}}</a>
                        <input type="file" class="d-none" id="import_author_file" accept=".csv"> | 
                        <a href="{{route('download-dummy-author-csv')}}" class="text-success" title="Download Example Author CSV"><i class="fas fa-file-download"></i> {{trans('message.download_example_author_csv')}}</a>                    
                        <div class="float-right">
                            <a href="{{route('authorCreate')}}" class="btn btn-block btn-primary">{{trans('message.add_author')}}</a>
                        </div>
                    </div>
                @endif
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="card-body">
                    <div id="chk_container" >
                        <div class="hide-show-section row">
                            <div class="col-sm-12 col-md-8"> 
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="chk_container_select">{{trans('message.hide_show_column')}}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="multi-select" name="headerList[]" multiple id="chk_container_select"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4"> 
                                <a href="javascript:void(0);" data-action ="delete" class="btn btn-danger custom-action"><i class="fas fa-trash"></i> {{trans('message.delete')}}</a>
                                <a href="javascript:void(0);" data-action ="active" class="btn btn-success custom-action"><i class="fas fa-eye"></i> {{trans('message.active')}}</a>
                                <a href="javascript:void(0);" data-action ="inactive" class="btn btn-primary custom-action"><i class="fas fa-eye-slash"></i> {{trans('message.inactive')}}</a>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="table-responsive mt-10">
                        <table id="example" class="display table table-bordered table-hover myDataTable">
                            <thead>
                                <tr>
                                    <th class="notexport"></th>
                                    <th>{{trans('message.first_name')}}</th>
                                    <th>{{trans('message.last_name')}}</th>
                                    <th>{{trans('message.email')}}</th>
                                    <th>{{trans('message.birth_date')}} <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="{{trans('message.your_birth_date')}}"></i></th>
                                    <th>{{trans('message.country')}}</th>
                                    <th>{{trans('message.type')}}</th>
                                    <th class="notexport">{{trans('message.status')}}</th>
                                    <th class="notexport">{{trans('message.action')}}</th>
                                </tr>
                            </thead>        
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
<!-- /.row -->
</div>


@stop

@section('javascript') 

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="{{asset('global_assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('global_assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('global_assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('global_assets/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('global_assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('global_assets/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('global_assets/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('global_assets/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('global_assets/plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('global_assets/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('global_assets/plugins/jquery-multiSelect-master/jquery.multiselect.js')}}"></script>
<script src="{{asset('global_assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
<script type="text/javascript" src="{!! url('resource', ['js','authors_js']); !!}"></script>
<script>
    window.route_toggle_status = "{{ route('authorToggleStatus') }}";
</script>
@endsection