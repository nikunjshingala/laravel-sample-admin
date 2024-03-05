@inject('request', 'Illuminate\Http\Request')

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{route('dashboard')}}" class="nav-link">{{trans('message.home')}}</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    @if(Auth::check())
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown dropdown-user">
            <a class="nav-link dropdown-toggle cursor-pointer" data-toggle="dropdown">
                <div class="image float-left">
                    @php
                        $imageName = Auth::user()->profile;
                        if(empty(Auth::user()->profile)) 
                            $imageName = 'default.png';
                        
                        $image = asset('storage/user_profile/'.$imageName);
                        
                    @endphp
                    <img src="{{$image}}" class="img-circle elevation-2 hw34" alt="User Image">
                </div>
                &nbsp;
                <div class="top-right-menu">
                    @if (Auth::check()) {{ Auth::user()->name }} @else {{trans('message.admin')}} @endif
                    <i class="caret"></i>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                <a class="nav-link dropdown-item" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt mr-2"></i> {{trans('message.full_screen')}}
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{route('userSettings')}}" class="dropdown-item {{$request->segment(1) == 'user-settings' ? 'active' : ''}}">
                    <i class="fas fa-cogs mr-2"></i> {{trans('message.user_setting')}}
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{route('auth.logout')}}" class="dropdown-item">
                    <i class="fas fa-sign-out-alt mr-2"></i> {{trans('message.logout')}}
                </a>
            </div>
        </li>
    </ul>
    @endif
</nav>