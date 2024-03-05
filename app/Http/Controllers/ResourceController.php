<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /**
     * Load file from public folder as per give foldertype and filename.
     *
     * @param  \Illuminate\Http\Request $request
     */
    public function index(Request $request) {
        include_once(public_path() . '/' . $request->d . '/' . $request->f.'.php');
    }
    /**
     * Used to show the error page
     *
     * @return view
     */
    public function error()
    {
        return view('errors.403');
    }
}
