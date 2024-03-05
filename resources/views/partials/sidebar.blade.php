@inject('request', 'Illuminate\Http\Request')

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('dashboard')}}" class="brand-link">
        <img src="{{asset('storage/')}}/{{config('constants.SITE_LOGO')}}" class="site-logo" alt="{{config('constants.SITE_NAME')}}" title="{{config('constants.SITE_NAME')}}"><span class="brand-text font-weight-light"> {{config('constants.SITE_NAME')}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
                @php
                    
                    //get current logged in user's group id
                    $userId = Auth::check() ? Auth::user()->id : '' ;
                    $chk_menu_at_last = $menuPermission = Utility::getUserPermission($userId);
                    if(!empty($menuPermission->count())) {
                        $menuPermission = explode(',', $menuPermission[0]->allow_view);
                    }

                    @include app_path('Http/Helpers/menu_list.php');
                    //this $menu accessing from the above menu_list.php file
                    //dd($menu);
                    if(count($menuPermission) > 0) {
                        foreach ($menu as $key => $value)
                        {
                            //get menu detail
                            $menuName = $value['mainModule'];
                            $menuUrl = $value['menuURL'];
                            $mainMenuIcon = $value['icon'];
                            $menuRouteName = $value['routeName'];
                            $param = !empty($value['param']) ? $value['param'] : '';
                            $showMenu = false;
                            if(!empty($menuUrl))
                            {
                                if(in_array($key, $menuPermission))
                                {
                                @endphp
                                    <li class="nav-item">
                                        <a href="{{url('/'.$menuRouteName)}}" class="nav-link {{ $request->segment(1) == $menuRouteName ? 'active' : '' }}">
                                            <i class="nav-icon fas {{ $mainMenuIcon }}"></i>
                                            <p>{{ $menuName }}</p>
                                        </a>
                                    </li>
                                @php
                                }
                            }
                            else
                            {
                                $showMenu = false;
                                $routeList = array_column($value["menus"], 'routeName');
                                foreach($value["menus"] as $key1 => $value1)
                                {
                                    $subMenuName = $value1['menuName'];
                                    $menuIcon = $value1['icon'];
                                    $menuRouteName = $value1['routeName'];
                
                                    if(empty($mainMenuIcon)){
                                        $mainMenuIcon = $menuIcon;
                                    }
                                    //if key is available in array then submenu diplay
                                    if(in_array($key1, $menuPermission))
                                    {
                                        $showMenu = true;
                                        break;
                                    }
                                }
                                if(!empty($showMenu)){
                                @endphp
                                    <li class="nav-item {{ in_array($request->segment(1),$routeList) ? 'menu-open' : '' }}">
                                        <a href="#" class="nav-link {{ in_array($request->segment(1),$routeList) ? 'active' : '' }}">
                                            <i class="nav-icon fas {{ $mainMenuIcon }}"></i>
                                            <p>
                                                {{ $menuName }}
                                                <i class="right fas fa-angle-left"></i>
                                            </p>
                                        </a>
                                @php
                                }
                            }
                            if(empty($menuUrl))
                            {
                                if(!empty($showMenu))
                                {
                                @endphp
                                    <ul class="nav nav-treeview">
                                @php
                                    foreach($value["menus"] as $key1 => $value1)
                                    {
                                        $subMenuName = $value1['menuName'];
                                        $menuIcon = $value1['icon'];
                                        $conditionMenuRouteName = $menuRouteName = $value1['routeName'];
                                        if(in_array($key1, $menuPermission))
                                        {
                                        $segmentValue = $request->segment(1);

                                        @endphp
                                        <li class="nav-item">
                                            <a href="{{url('/'.$menuRouteName)}}" class="nav-link {{ $segmentValue == $conditionMenuRouteName ? 'active' : '' }}">
                                                <i class="fas {{ $menuIcon }} nav-icon"></i>
                                                <p>{{ $subMenuName }}</p>
                                            </a>
                                        </li>
                                        @php
                                        }
                                    }
                                @endphp
                                    </ul>
                                @php
                                }
                            }
                            @endphp
                            {!! !empty($showMenu) ? '</li>' : ''!!}
                            @php
                        }
                    }
                @endphp
                <li class="nav-item">
                    <a href="{{url('/api/documentation')}}" target="_blank" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>{{trans('message.api_document')}}</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>