<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestRouteController extends Controller
{
    /**
     * Used to show route1 page
     *
     * @param Request $request
     * @return view
     */
    public function route1(Request $request)
    {
        return view('route1');
    }
    /**
     * Used to show route2 page
     *
     * @param Request $request
     * @return view
     */
    public function route2(Request $request)
    {
        return view('route2');
    }
}
