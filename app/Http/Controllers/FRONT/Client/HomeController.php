<?php

namespace App\Http\Controllers\FRONT\Client;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Service;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /** Functions
     * index()
     * service()
     */

    public function index( Request $request ){
        $posts = Post::orderBy('created_at','desc')->take(3)->get();
        return view ('front.client.home',compact('posts'));
    }

    public function service( Request $request , $id = null){
        $user = Auth::user();
        if ( $id == null ){
            $services = Service::all();
            return view ('front.client.services', compact('services'));
        }else{
            $service = Service::query()->find($id);
            $employees = $service->users;
            return view ('front.client.service-users', compact(['service','user','employees']));
        }
    }
    public function filterUsers(Request $request, $id){
        $user = Auth::user();
        $service = Service::query()->find($id);
        foreach ($user->client_addresses as $address){
            if ($address['_id']==$request->address){
                $lng= $address['location']['0'];
                $lat= $address['location']['1'];
            }
        }
        $employees = User::query()
            ->where('role', 'user_employee')
            ->orWhere('role', 'employee')
            ->where('isApproved', true)
            ->where('location', 'near', [
                '$geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        (float)$lng,
                        (float)$lat,
                    ],
                    'distanceField' => "dist.calculated",
                    '$maxDistance' => 50,
                ],
            ])->orderBy('dist.calculated')
            ->get();
        if($request->availableTimes != null) {
            foreach($request->availableTimes  as $availableTime) {
                $elements = explode(', ',$availableTime );
                $availableTimes = array([
                    'date' => $elements[0],
                    'from' => $elements[1],
                    'to' => $elements[2]
                ]);
            }
        }
        else{
            $availableTimes = array();
        }
        if(isset($request->date) && isset($request->from) && isset($request->to)){
            array_push($availableTimes,array(
                'date'=>$request->date,
                'from'=>$request->from,
                'to'=>$request->to
                )
            );
        }
        if(!empty($availableTimes)){
            $index = 0;
            foreach($employees as $employee) {
                foreach ($availableTimes as $available) {
                    $day = date('w', strtotime($available['date']));
                    $day++;
                    if($day==7){
                        $day=0;
                    }
                    foreach ($employee->employeeRequests as $request){
                        if($request->isdone==false & $request->date->format('m/d/Y') == $available['date'] ){
                            for($from=$request->from; $from<$request->to;$from++) {
                                $employee->timeline[$day][$from]=false;
                            }
                        }
                    }
                    for($from=$available['from'];$from<$available['to'];$from++) {
                        if ($employee->timeline[$day][$from]==false){
                            unset($employees[$index]);
                        }
                        }
                }
                $index++;
            }
        }
        $keyword = $request->keyword;
        $address=$request->address;
        return view ('front.client.service-users', compact(['service','user','keyword','address','availableTimes','employees']));
    }
}
