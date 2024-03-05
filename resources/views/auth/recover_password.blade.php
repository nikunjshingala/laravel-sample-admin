@extends('app')

@section('pageTitle', 'Recover Password')
@section('contentTitle', 'Recover Password')
@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>
        <form action="{{ route('recover-password-post') }}" method="POST" id="recover_password_form" name="recover_password_form" autocomplete="off" enctype="multipart/form-data">
            @csrf
            <div class="mb-2">
                <div class="input-group">
                    <input class="form-control remove-flex" readonly="true" type="email" value="{{ old('email', \request()->email) }}" id="email" name="email" placeholder="{{trans('message.email')}}">
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
            <div class="mb-2">
                <div class="input-group">
                    <input class="form-control remove-flex" type="password" value="" id="password" name="password" placeholder="{{trans('message.password')}}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div id="password_validate"></div>
                @error('password')
                    <div class="error">{{ $errors->first('password') }}</div>
                @enderror
            </div>
            <div class="mb-2">
                <div class="input-group">
                    <input class="form-control remove-flex" type="password" value="" id="password_confirmation" name="password_confirmation" placeholder="{{trans('message.confirm_password')}}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div id="password_confirmation_validate"></div>
                @error('password_confirmation')
                    <div class="error">{{ $errors->first('password_confirmation') }}</div>
                @enderror
            </div>
            <input type="hidden" name="token" value="{{ old('token', \request()->token) }}">
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">{{trans('message.change_password')}}</button>
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
<script type="text/javascript" src="{!! url('resource', ['js','recover_password']); !!}"></script>
@endsection