<?php
$menu =array (
    
    1 => array (
        'mainModule' => trans('message.dashboard'),
        'menuURL' => 'DashboardController',
        'routeName' => 'dashboard',
        'icon' => 'fa-tachometer-alt',
        'param' => ''),
    2 => array (
        'mainModule' => trans('message.authors'),
        'menuURL' => 'AuthorController',
        'routeName' => 'author',
        'icon' => 'fa-at',
        'param' => ''),
    3 => array (
        'mainModule' => trans('message.users'),
        'menuURL' => 'UserController',
        'routeName' => 'user',
        'icon' => 'fa-user',
        'param' => ''),
    4 => array (
        'mainModule' => trans('message.main_menu'),
        'menuURL' => '',
        'routeName' => '',
        'icon' => 'fa-bars',
        'param' => '',
        'menus' =>
        array (
            5 => array (
                'menuName' => trans('message.sub_menu_one'),
                'menuURL' => 'TestRouteController',
                'routeName' => 'route1',
                'icon' => 'fa-ellipsis-h',
            ),
            6 => array (
                'menuName' => trans('message.sub_menu_two'),
                'menuURL' => 'TestRouteController',
                'routeName' => 'route2',
                'icon' => 'fa-ellipsis-v',
            ),
        ),
    ),
);
?>