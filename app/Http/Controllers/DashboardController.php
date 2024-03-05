<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\User;
use DB;
use Config;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    /**
     * Used to show the dashboard
     *
     * @return view
     */
    public function index(Request $request)
    {
        Config::set('app.timezone', 'UTC');
        Artisan::call('config:clear');
        $data = array();
        $data['authorCount'] = Author::count();
        $data['userCount'] = User::where('status','!=','deleted')->count();
        // $sql = "SELECT MONTHNAME(`birthdate`) as month,concat('User Type ',type) AS usertype,count(`birthdate`) AS count FROM authors GROUP BY MONTH(birthdate),type ORDER BY MONTH(birthdate)";
        // $itemList = DB::select( DB::raw($sql));
        $sql = "SELECT MONTHNAME(`birthdate`) AS month, CONCAT('User Type ', type) AS usertype, COUNT(`birthdate`) AS count FROM authors GROUP BY MONTH(birthdate), type ORDER BY MONTH(birthdate)";

        $itemList = DB::select($sql);

        $itemList = collect($itemList);
        $monthList = $itemList->pluck('month')->unique()->values()->implode('","');
        $gbuserType = $itemList->groupBy('usertype');
        $mainChartArray = [];
        foreach ($gbuserType as $gbutkey => $gbutvalue) {
            $innerChartArray = array();
            $innerChartArray['label'] = $gbutkey;
            $innerChartArray['data'] = $gbutvalue->pluck('count')->implode(',');
            $mainChartArray[] = $innerChartArray;
        }

        $donutChartQuery = Author::select('country',DB::raw('count(*) as data'),DB::raw('CONCAT("#",SUBSTR(MD5(RAND()*'.random_int(100000, 999999).'), 1, 6)) AS color'))->groupBy('country')->get();
        $donutChartData = array();
        $donutChartData['country'] = $donutChartQuery->pluck('country')->implode('","');
        $donutChartData['color'] = $donutChartQuery->pluck('color')->implode('","');
        $donutChartData['data'] = $donutChartQuery->pluck('data')->implode(',');
        return view('dashboard.index',compact('data','monthList','mainChartArray','donutChartData'));
    }
}
