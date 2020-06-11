<?php


namespace App\Http\Controllers\CMS;


use App\Charts\Stats;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Redirect, Response;
use Carbon\Carbon;
use function GuzzleHttp\Promise\all;

class StatisticsController extends Controller
{
    public function index()
    {
        $users = User::query()->orderBy('visits');
        $chart = new Stats();
        $arr = [];
        $arr2 = [];
        foreach ($users as $user) {
            if ($user->visits != null) {
                array_push($arr2, $user->name);
                array_push($arr, $user->visits);
            }
        }
        $chart->labels($arr2);
        $chart->dataset('Most Visited Handyman', 'line', $arr)->color("rgb(255, 99, 132)")->backgroundcolor("rgb(255, 99, 132)");

        $chart2 = new Stats();
        $arr = [];
        $arr2 = [];
        foreach ($users as $user) {
            if ($user->gender != null) {
                array_push($arr2, $user->name);
                array_push($arr, $user->gender);
            }
        }
        $chart2->labels($arr2);
        $chart2->dataset('Genders', 'pie', $arr)->color("rgb(255, 99, 132)")->backgroundcolor("rgb(255, 99, 132)");


        return view('cms.statistics.index', compact('chart', 'chart2'));
    }


}
