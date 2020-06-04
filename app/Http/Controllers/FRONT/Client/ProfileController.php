<?php

namespace App\Http\Controllers\FRONT\Client;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /** Functions
     * myProfile()
     * editPassword()
     * editPayment()
     * updateImage()
     * destroyImage()
     * updateContact()
     * createAddress()
     * storeAddress()
     * editAddress()
     * updateAddress()
     * destroyAddress()
     * employeeProfile()
     * allReviews()
     */

    public function myProfile()
    {
        $user = Auth::user();
        return view('front.client.profile.edit-profile', compact('user'));
    }

    public function editPassword()
    {
        $user = Auth::user();
        return view('front.client.profile.password', compact('user'));
    }

    public function editPayment()
    {
        $user = Auth::user();
        return view('front.client.profile.payment', compact('user'));
    }

    //Client Profile Image
    public function updateImage(Request $request)
    {
        $user = Auth::user();
        $file = $request->file('image-input');
        $name = 'image_' . time() . '.' . $file->getClientOriginalExtension();
        if (!Storage::disk('public')->exists('profile')) {
            Storage::disk('public')->makeDirectory('profile');
        }
        if (Storage::disk('public')->putFileAs('profile', $file, $name)) {
            $user->image = 'profile/' . $name;
        } else {
            return view('front.client.profile.edit-profile', compact('user'));
        }
        $user->save();
        return redirect()->route('client.profile');
    }

    public function destroyImage()
    {
        $user = Auth::user();
        $user->image = "";
        $user->save();
        return redirect()->route('client.profile');
    }

    //Client Contact Information
    public function updateContact(Request $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();
        return redirect()->route('client.profile');
    }

    // functions for Client Addresses
    public function createAddress(Request $request)
    {
        $user = Auth::user();
        return view('front.client.profile.create-address', compact('user'));
    }

    public function storeAddress(Request $request)
    {
        $user = Auth::user();
        $data = [
            "_id" => Str::random(24),
            "name" => $request->name,
            "type" => $request->type,
            "location" => [$request->lat, $request->lng],
            "street" => $request->street,
            "house" => $request->house,
            "zip" => $request->zip,
            "property_type" => $request->property,
            "contract_type" => $request->contract,
        ];
        $user->push('client_addresses', $data);
        $user->save();
        $user = Auth::user();
        return redirect()->route('client.profile');
    }

    public function editAddress($id)
    {
        $user = Auth::user();
        $address = null;
        foreach ($user->client_addresses as $client_address) {
            if ($client_address['_id'] == $id) {
                $address = $client_address;
                break;
            }
        }
        return view('front.client.profile.edit-address', compact(['user', 'address']));
    }

    public function updateAddress($id)
    {
        $user = Auth::user();
        // $user->push('locations', '');
        return view('front.client.profile.edit-profile', compact('user'));
    }

    public function destroyAddress($id)
    {
        $user = Auth::user();
        // $user->push('locations', '');
        return view('front.client.profile.edit-profile', compact('user'));
    }
    //end of functions for Client addresses


    //functions for employee Profiles

    public function employeeProfile($id, $employee_id)
    {
        $service = Service::find($id);
        $employee = User::find($employee_id);
        $rf = array();
        $rs = array();
        foreach ($employee->services as $service) {
            for ($index = 0; $index < 7; $index++) {
                $rs[$service->id][$index] = 0;
            }
        }
        foreach ($employee->services as $service) {
            $index = 0;
            $total = 0;
            $bool = false;
            foreach ($employee->employeeRequests as $request) {
                if ($request->service_id == $service->id) {
                    if ($request->rating != null) {
                        $bool = true;
                        $client = User::find($request->client_ids[0]);
                        $rf[$service->id][$index] = [
                            'rating' => $request->rating,
                            'feedback' => $request->feedback,
                            'client' => [
                                'name' => $client->name,
                                'image' => $client->image,
                            ]
                        ];
                        $total += $request->rating;
                        $index++;
                        if ($request->rating > 4) {
                            $rs[$service->id][5]++;
                        } elseif ($request->rating > 3) {
                            $rs[$service->id][4]++;
                        } elseif ($request->rating > 2) {
                            $rs[$service->id][3]++;
                        } elseif ($request->rating > 1) {
                            $rs[$service->id][2]++;
                        } else {
                            $rs[$service->id][1]++;
                        }
                    }
                }
            }
            if ($bool == true) {
                $rs[$service->id][0] += $total / $index;
                $rs[$service->id][6] += $index;
            }
        }
$feedbacks = $rf;
        $service_rating=$rs;
        return view('front.client.employee-profile', compact(['employee', 'service', 'feedbacks', 'service_rating']));
    }

    public function allReviews($id, $employee_id)
    {
        $employee = User::find($employee_id);
        return view('front.client.see-all-reviews', compact('employee'));
    }
}
