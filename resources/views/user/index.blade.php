@inject('request', 'Illuminate\Http\Request')
@extends('app')

@section('pageTitle', trans('message.user_list'))
@section('contentTitle', trans('message.user_list'))

@section('customcss')
@endsection
@section('content')

<section class="content">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    @if(!empty($request->accessMethod['add']))
                    <div class="float-right">
                      <a href="{{route('userCreate')}}" class="btn btn-block btn-primary">{{trans('message.add_user')}}</a>
                    </div>
                    @endif
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="card-body">
                    <!-- /.card-header -->
                    <div class="table-responsive">
                        <table id="example" class="display table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{{trans('message.name')}}</th>
                                    <th>{{trans('message.email')}}</th>
                                    <th>{{trans('message.gender')}}</th>
                                    <th>{{trans('message.type')}}</th>
                                    <th>{{trans('message.status')}}</th>
                                    <th>{{trans('message.action')}}</th>
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
<script src="{{asset('global_assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('global_assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('global_assets/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" src="{!! url('resource', ['js','user']); !!}"></script>
<script>
    window.route_toggle_status = "{{ route('userToggleStatus') }}";
</script>
@endsection