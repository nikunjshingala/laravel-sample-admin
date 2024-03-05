@extends('app')

@section('pageTitle', trans('message.sub_menu_one'))
@section('contentTitle', trans('message.sub_menu_one'))

@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            {{trans('message.sub_menu_one')}}
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@stop

@section('javascript')

@endsection