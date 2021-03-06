<?php

namespace App\Http\Controllers\FRONT\Client;

use App\Events\NotificationSenderEvent;
use App\Http\Controllers\Controller;
use App\Models\RequestService;
use App\Models\Service;
use App\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RequestController extends Controller
{
    /** Functions
     * index()
     * create()
     * store()
     * show()
     * edit()
     * update()
     * destroy()
     * validator()
     * notification()
     * smart_wordwrap()
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $pendingRequests = Auth::user()->clientRequests()->where('status', 'pending')->where('isdone',false)->get();
        $approvedRequests = Auth::user()->clientRequests()->where('status', 'approved')->where('isdone',false)->get();
        $rescheduledRequests = Auth::user()->clientRequests()->where('status', 'rescheduled')->where('isdone',false)->get();

        $pendingRequests = $pendingRequests->map(function ($item) {
            $item->service_name = Service::find($item->service_id)->name;
            if(!empty($item->employee_ids)) {
                $item->employee = User::find($item->employee_ids[0]);
            }
            return $item;
        });
        $approvedRequests = $approvedRequests->map(function ($item) {
            $item->service_name = Service::find($item->service_id)->name;
            if(!empty($item->employee_ids)) {
                $item->employee = User::find($item->employee_ids[0]);
            }
            return $item;
        });
        $rescheduledRequests = $rescheduledRequests->map(function ($item) {
            $item->service_name = Service::find($item->service_id)->name;
            $item->employee = User::find($item->employee_ids[0]);
            return $item;
        });
        return view('front.client.request.index', compact(['pendingRequests', 'approvedRequests']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        if ($request->input('employee_id') == null) {
            $services = Service::all();
            return view('front.client.request.create', compact(['user', 'services']));
        }
        $employee = User::find($request->input('employee_id'));
        if($request->input('service_id')!=null) {
            $service = Service::find($request->input('service_id'));
        }
        $startDate = new DateTime('now');
        $Days = array();
        $date = $startDate;
        $bool = false;
        $availableDaysString = "";
        $timepicker = array();
        for ($x = 0; $x < 24; $x++) {
            $day = date('w', strtotime($date->format('Y-m-d')));
            $day--;
            if($day==-1){
                $day=6;
            }
            for ($hour = 0; $hour < 24; $hour++) {
                $Days[$date->format('m/d/Y')][$hour] = $employee->timeline[$day][$hour];
                if ($Days[$date->format('m/d/Y')][$hour] == true) {
                    $bool = true;
                }
            }
            foreach ($employee->employeeRequests as $employeeRequest) {
                if ($employeeRequest->isdone == false && $employeeRequest->date->format('m/d/Y') == $date->format('m/d/Y')) {
                    for ($from = $employeeRequest->from; $from < $employeeRequest->to; $from++) {
                        $Days[$date->format('m/d/Y')][$from] = false;
                    }
                }
            }
            for ($hour = 0; $hour < 24; $hour++) {
                if ($Days[$date->format('m/d/Y')][$hour] == true) {
                    $bool = true;
                }
            }
            if ($bool == false) {
                unset($Days[$date->format('m/d/Y')]);
            } else {
                if ($availableDaysString == "") {
                    $availableDaysString = $date->format('m/d/Y');
                } else {
                    $availableDaysString = $availableDaysString . ',' . $date->format('m/d/Y');
                }
                $timepicker[$date->format('m/d/Y')] = array();
                $break = false;
                $index = 0;
                $from = 0;
                for ($hour = 0; $hour < 24; $hour++) {
                    if ($Days[$date->format('m/d/Y')][$hour] == true) {
                        if ($break == true && !empty($timepicker[$date->format('m/d/Y')])) {
                            $index++;
                        }
                        $break = false;
                        $timepicker[$date->format('m/d/Y')][$index] = array(
                            'from' => $from,
                            'to' => $hour + 1
                        );
                    } else {
                        $break = true;
                        $from = $hour + 1;
                    }
                }
            }
            $bool = false;
            $date->modify('+1 day');
        }
        return view('front.client.request.create', compact(['user', 'employee', 'service', 'availableDaysString', 'timepicker']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $req
     * @param $employee_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $req)
    {
        $user = Auth::user();
        $req->validate([
            'subject' => 'required:min:3',
            'description' => 'required|min:15'
        ]);
        $requestHandyman = new RequestService();
        $requestHandyman->subject = $req->input('subject');
        $requestHandyman->description = $this->smart_wordwrap($req->input('description'), 37)." ";
        $requestHandyman->status = 'pending';
        $requestHandyman->isdone = false;
        $requestHandyman->timezone = $req->timezone;//'Asia\Beirut'
        $requestHandyman->service_id = $req->input('service');
        //  $requestHandyman->location = explode(',', $req->input('location'));
        $address = null;
        foreach ($user->client_addresses as $client_address) {
            if ($client_address['_id'] == $req->address) {
                $address = $client_address;
                break;
            }
        }
        $requestHandyman->client_address = $address;
        if ($imagesParam = $req->file('images')) {
            $images = array();
            foreach ($imagesParam as $image) {
                $name = 'image_' . time(). Str::random(16) . '.' . $image->getClientOriginalExtension();
                if (!Storage::disk('public')->exists('requests')) {
                    Storage::disk('public')->makeDirectory('requests');
                }
                if (Storage::disk('public')->putFileAs('requests', $image, $name)) {
                    $element = 'requests/' . $name;
                    array_push($images, $element);
                }
            }
            $requestHandyman->images = $images;
        }

        if (!$req->has('employee_id')) {
            if ($req->is_urgent == true) {
                $requestHandyman->isurgent = true;
                if (Carbon::now($requestHandyman->timezone)->minute > 30) {
                    $nowHour = str_pad(Carbon::now($requestHandyman->timezone)->hour + 1, 2, '0', STR_PAD_LEFT) . '00';
                    $nowNextHour = str_pad(Carbon::now($requestHandyman->timezone)->hour + 2, 2, '0', STR_PAD_LEFT) . '00';
                } else {
                    $nowHour = str_pad(Carbon::now($requestHandyman->timezone)->hour, 2, '0', STR_PAD_LEFT) . '00';
                    $nowNextHour = str_pad(Carbon::now($requestHandyman->timezone)->hour + 1, 2, '0', STR_PAD_LEFT) . '00';
                }
                $requestHandyman->from = $nowHour;
                $requestHandyman->to = $nowNextHour;
                $requestHandyman->date = Carbon::createFromFormat('Y-m-d', date("Y-m-d"), $requestHandyman->timezone);

            } else {
                $requestHandyman->isurgent = false;
                $requestHandyman->from = $req->from;
                $requestHandyman->to = $req->to;
                $dateArray= explode("/",$req->date);
                $requestDate = $dateArray[2].'-'.$dateArray[0].'-'.$dateArray[1];
                $requestHandyman->date = Carbon::createFromFormat('Y-m-d',$requestDate, $requestHandyman->timezone);

            }
            $requestHandyman->save();
            $requestHandyman->clients()->attach($user);

            return redirect(route('client.request.index'));
        } else {
            $handyman = User::query()->find($req->input('employee_id'));
            $handyman->employee_requests_count = $handyman->employee_requests_count +1;
            $handyman->save();
            $requestHandyman->price = $handyman->price;
            $dateArray= explode("/",$req->date);
            $requestDate = $dateArray[2].'-'.$dateArray[0].'-'.$dateArray[1];
            $requestHandyman->date = Carbon::createFromFormat('Y-m-d',$requestDate, $requestHandyman->timezone);
            $requestHandyman->isurgent = false;
            if ($req->has('from'))
                $requestHandyman->from = $req->input('from');
            if ($req->has('to')) {
                $requestHandyman->to = $req->input('to');
            } else {
                $requestHandyman->handyman_to = 'pending';
                $requestHandyman->to = null;
            }
            if($req->recurring == true){
                //loop through them reschedule if needed status== reschedule
            }

            $this->notification($handyman->device_token, Auth::user()->name, 'You received a new request', 'request');

            $requestHandyman->save();
            $requestHandyman->clients()->attach($user);
            $handyman = User::query()->find($req->input('employee_id'));
            $requestHandyman->employees()->attach($handyman);

        }
        return redirect(route('client.request.index'));
    }

//    public function acceptRescheduled($id){
//        $job = RequestService::findOrFail($id);
//        $job->status = "approved";
//        $job->save();
//        return  redirect(route('client.request.index'));
//    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        //
        return view('front.client.request');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        //
        return view('front.client.request');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        //
        $user = Auth::user();
        $request = RequestService::find($id);
        $request->clients()->detach($user->id);
        if($request->employees()->count() > 0) {
            $employee = User::find($request->employee_ids[0]);
            $request->employees()->detach($employee);
            }
        $request->delete();
        return redirect(route('client.request.index'));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'description' => ['required', 'string', 'min:15']
        ]);
    }

    public function Notification($to, $from, $message, $type)
    {
        $notification = array();
        $notification['to'] = $to;
        $notification['user'] = $from;
        $notification['message'] = $message;
        $notification['type'] = $type;// maybe "notification", "comment(message)", "request","message"
        $notification['object'] = [];

        event(new NotificationSenderEvent($notification));

        return response()->json(['status' => 'success', 'notification' => $notification]);
    }
    function smart_wordwrap($string, $width = 40, $break = " ") {
        $pattern = sprintf('/([^ ]{%d,})/', $width);
        $output = '';
        $words = preg_split($pattern, $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        foreach ($words as $word) {
            if (false !== strpos($word, ' ')) {
                $output .= $word;
            } else {
                $wrapped = explode($break, wordwrap($output, $width, $break));
                $count = $width - (strlen(end($wrapped)) % $width);

                $output .= substr($word, 0, $count) . $break;

                $output .= wordwrap(substr($word, $count), $width, $break, true);
            }
        }
        return wordwrap($output, $width, $break);
    }
}

