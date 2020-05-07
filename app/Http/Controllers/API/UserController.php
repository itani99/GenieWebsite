<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use phpDocumentor\Reflection\Types\Integer;

class UserController extends Controller
{
    //

    public function setDeviceToken(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, ['device_platform' => 'required']);
        $params = $request->all();
        foreach ($params as $param => $value) {
            $user->{$param} = $value;
        }
        $user->save();

        return response()->json(['status' => 'success']);

    }

    public function getProfile()
    {
        $user = Auth::user();

        return response()->json(['status' => 'success', 'profile' => $user]);

    }

    public function getTimeline($id)
    {
        $user = User::query()->find($id);
        $timeline = $user->timeline;
        return response()->json(['status' => 'success', 'timeline' => $timeline]);
    }

//public function
    public function editProfile(Request $request)
    {
        $user = User::query()->find(Auth::id());

        $params = $request->only([
// common info
            'addresses',
            'image',
            'latitude', 'longitude',
            'birth_date',
            'gender',
            'bank_account',
// user extra info
            'from',
            'to',
            'payment_method',
            'apartment_details',
// employee extra info
            'cv',
            'certificate',
            'timeline',
            'criminal_record',
            'service',
            'biography'


        ]);

        if (Arr::has($params, 'addresses')) {
            $addresses = $user->addresses;
            if ($addresses == null) {
                $addresses = [];
                $addresses[0] = [(double)$request->long, (double)$request->lat];

            } else {
                array_push($addresses, [(double)$request->long, (double)$request->lat]);

            }
            $user->addresses = $addresses;
        }
        if (Arr::has($params, 'image'))
            $user->image = $this->uploadAny($params['image'], 'profile');

        if (Arr::has($params, 'biography'))
            $user->biography = $params['biography'];

        if (Arr::has($params, 'birth_date'))
            $user->birth_date = $params['birth_date'];

        if (Arr::has($params, 'latitude') && Arr::has($params, 'longitude')) {
            $latitude = $request->input('latitude');

            $longitude = $request->input('longitude');
            $location = [];
            $location[0] = (double)$longitude;
            $location[1] = (double)$latitude;
            $user->location = $location;


        }

        if (Arr::has($params, 'gender'))
            $user->gender = $params['gender'];

        if (Arr::has($params, ['from', 'to'])) {
            $user->time_preferences_start = $params['from'];
            $user->time_preferences_end = $params['to'];
        }

        if (Arr::has($params, 'payment_method'))
            $user->payment_method = $params['payment_method'];

        if (Arr::has($params, 'apartment_details'))
            $user->apartment_details = $params['apartment_details'];

        if (Arr::has($params, 'cv'))
            $user->cv = $this->uploadAny($params['cv'], 'cv', 'pdf');
        if (Arr::has($params, 'certificate'))
            $user->certificate = $this->uploadAny($params['certificate'], 'certificates', 'pdf');
        if (Arr::has($params, 'criminal_record'))
            $user->criminal_record = $this->uploadAny($params['criminal_record'], 'criminal_records', 'pdf');

        if (Arr::has($params, 'timeline')) {
            $test = [];

            $days = $params['timeline'];
            $count = 0;
            foreach ($days as $day) {

                $day_decode = json_decode($day);
                $day_array = [];
                for ($i = 0; $i <= 23; $i++) {

                    $hour = str_pad($i,
                            2, 0, STR_PAD_LEFT) . "00";
                    if ($hour>=$day_decode[$i][0]['from']){
                        $day_array[$i]=true;
                    }else{
                        $day_array[$i]=false;
                    }
                   // $day_array[$i] = true;
//                    $day_array[$i] = $hour >= $day_decode[0]['from'] && $hour <= $day_decode[0]['to'];
                }
                $test[$count] = $day_array;
                $count++;
            }

            $user->test_timeline = $test;
//            $timeline = [];
//            for ($i = 0; $i <= 23; $i++) {
//                $hour = $i;
//
//                foreach ($params['timeline'][0] as $option) {
//                    $timeline[0][$hour] =
//                        $hour >= $params['timeline'][0][$option]['from']
//                        && $hour <= $params['timeline'][0][$option]['to'];
//
//                }
//
//                foreach ($params['timeline'][1] as $option) {
//                    $timeline[1][$hour] =
//                        $hour >= $params['timeline'][1][$option]['from']
//                        && $hour <= $params['timeline'][1][$option]['to'];
//
//                }
//
//                foreach ($params['timeline'][2] as $option) {
//                    $timeline[2][$hour] =
//                        $hour >= $params['timeline'][2][$option]['from']
//                        && $hour <= $params['timeline'][2][$option]['to'];
//
//                }
//
//                foreach ($params['timeline'][3] as $option) {
//                    $timeline[3][$hour] =
//                        $hour >= $params['timeline'][3][$option]['from']
//                        && $hour <= $params['timeline'][3][$option]['to'];
//
//                }
//
//                foreach ($params['timeline'][4] as $option) {
//                    $timeline[4][$hour] =
//                        $hour >= $params['timeline'][4][$option]['from']
//                        && $hour <= $params['timeline'][4][$option]['to'];
//
//                }
//
//                foreach ($params['timeline'][5] as $option) {
//                    $timeline[5][$hour] =
//                        $hour >= $params['timeline'][5][$option]['from']
//                        && $hour <= $params['timeline'][5][$option]['to'];
//
//                }
//
//                foreach ($params['timeline'][6] as $option) {
//                    $timeline[6][$hour] =
//                        $hour >= $params['timeline'][6][$option]['from']
//                        && $hour <= $params['timeline'][6][$option]['to'];
//
//                }
//
//
//            }
//            $user->timeline = $timeline;

        }

        $user->save();

        return response()->json(['status' => 'success', 'user' => $user]);

    }

    public function logout(Request $request)
    {
        auth::logout();

        return response()->json(['status' => 'success', 'message' => 'logged out']);
    }


    public function uploadAny($file, $folder, $ext = 'png')
    {
        $file = base64_decode($file);

        $file_name = Str::random(25) . '.' . $ext; //generating unique file name;
        if (!Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder);
        }
        $result = false;
        if ($file != "") { // storing image in storage/app/public Folder
            $result = Storage::disk('public')->put($folder . '/' . $file_name, $file);

        }
        if ($result)
            return $folder . '/' . $file_name;
        else
            return null;
    }
    /*
     */
}
