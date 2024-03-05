@extends('app')

@section('pageTitle', trans('message.sub_menu_two'))
@section('contentTitle', trans('message.sub_menu_two'))

@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            {{trans('message.sub_menu_two')}}
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@stop

@section('javascript')

@endsection