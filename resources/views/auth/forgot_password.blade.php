@extends('app')

@section('pageTitle', trans('message.forgot_password'))
@section('contentTitle', trans('message.forgot_password'))
@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">{{trans('message.forgot_password_title')}}</p>
        <form action="{{route('forgot-password')}}" method="POST" id="forgot_password_form" name="forgot_password_form" autocomplete="off" enctype="multipart/form-data">
            @csrf
            <div class="mb-2">
                <div class="input-group">
                    <input class="form-control remove-flex" type="text" value="{{ old('email') }}" id="email" name="email" placeholder="{{trans('message.email')}}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div id="email_validate"></div>
                @error('email')
                    <div class="error">{{ $errors->first('email') }}</div>
                @enderror
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">{{trans('message.request_new_password')}}</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <p class="mt-3 mb-1">
            <a href="{{route('login')}}">{{trans('message.login')}}</a>
        </p>
    </div>
    <!-- /.login-card-body -->
</div>
@stop
<!-- /.login-box -->
@section('javascript')
<script type="text/javascript" src="{!! url('resource', ['js','forgot_password']); !!}"></script>
@endsection