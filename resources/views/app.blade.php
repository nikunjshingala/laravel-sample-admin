<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="hold-transition {{ (Auth::check() ? 'sidebar-mini layout-fixed' : 'hold-transition login-page') }}">
        <div class="{{ (Auth::check() ? 'wrapper' : 'login-box') }}">
            <div class="common-loader" style="display: none;">
                <div class="dot"></div>
                <div class="dot d2"></div>
                <div class="dot d3"></div>
                <div class="dot d4"></div>
                <div class="dot d5"></div>
            </div>
            @if(Auth::check())
                <!-- Navbar -->
                @include('partials.navbar')
                <!-- /.navbar -->
                
                <!-- Main Sidebar Container -->
                @include('partials.sidebar')
                <!-- /.Main Sidebar Container -->
                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper page <?php echo @session('error_code') ?>">
                    <!-- Content Header (Page header) -->
                    <div class="content-header">
                        <div class="container-fluid">
                            @if(trim($__env->yieldContent('contentTitle')))
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0">@yield('contentTitle')</h1>
                                </div><!-- /.col -->
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{trans('message.home')}}</a></li>
                                        <li class="breadcrumb-item active">@yield('contentTitle')</li>
                                    </ol>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                            @endif
                        </div><!-- /.container-fluid -->
                    </div>
                    <!-- /.content-header -->
                    @yield('content')
                </div>
            @else
                <div class="login-logo">
                    <a href="{{url('/')}}"><img src="{{asset('storage/')}}/{{config('constants.SITE_LOGO')}}" class="site-logo" alt="{{config('constants.SITE_NAME')}}" title="{{config('constants.SITE_NAME')}}"><b>{{config('constants.SITE_NAME')}}</a>
                </div>
                @yield('content')
            @endif

            <!-- /Content Wrapper. Contains page content -->
            @if(Auth::check())
                @include('partials.footer')
            @endif
            @include('partials.javascripts')
            @yield('customscript')
        </div>
    </body>
</html>
