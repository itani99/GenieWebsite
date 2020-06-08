<?php


namespace App\Http\Controllers\CMS;


use App\Charts\Stats;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Redirect, Response;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        $users = User::query()->where('role', 'user_employee' || 'employee');
        $chart = new Stats();
        $arr = [];
        $arr2 = [];
        foreach ($users as $user) {
            array_push($arr2, $user->name);
            array_push($arr, $user->created_at);
        }
        $chart->labels($arr2);
        $chart->dataset('My Dataset', 'line', $arr);

        //$chart->dataset( $arr);
//        $chart->color("rgb(255, 99, 132)");
//        $chart->backgroundcolor("rgb(255, 99, 132)");

        return view('cms.statistics.index', compact('chart'));
    }


}
