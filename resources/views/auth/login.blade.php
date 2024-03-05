@extends('app')

@section('pageTitle', trans('message.login'))
@section('contentTitle', trans('message.login'))
@section('content')
<!-- /.login-logo -->
<div class="card">
  <div class="card-body login-card-body">
    <p class="login-box-msg">{{trans('message.login_title')}}</p>

    <form action="{{ route('login') }}" method="POST" id="login_form" name="login_form" autocomplete="off" enctype="multipart/form-data">
      @csrf
      <div class="mb-2">
        <div class="input-group">
          <input class="form-control remove-flex" type="text" value="{{ old('email', \request()->cookie('email')) }}" id="email" name="email" placeholder="{{trans('message.email')}}">
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
        <div class="input-group ">
          <input type="password" class="form-control remove-flex" placeholder="{{trans('message.password')}}" type="password" id="password" value="{{ old('password', \request()->cookie('password')) }}" name="password">
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
      @php
      $remember = \request()->cookie('remember');
      $remember_me = '';
      if($remember == 1) {
      $remember_me = 'checked';
      }
      @endphp
      <div class="row">
        <div class="col-8">
          <div class="icheck-primary">
            <input type="checkbox" value="1" name="remember" id="remember" {{$remember_me}}>
            <label for="remember">
            {{trans('message.remember_me')}}
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-4">
          <button type="submit" class="btn btn-primary btn-block">{{trans('message.login')}}</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <!-- /.social-auth-links -->

    <p class="mb-1">
      <a href="{{route('forgot-password')}}">{{trans('message.i_forgot_my_password')}}</a>
    </p>
  </div>
  <!-- /.login-card-body -->
</div>
@stop
<!-- /.login-box -->
@section('javascript')
<script type="text/javascript" src="{!! url('resource', ['js','login']); !!}"></script>
@endsection