<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\RequestService;
use App\User;
use Illuminate\Http\Request;

class RequestController extends Controller
{


    public function index()
    {
        //
        return view('cms.requests.index', ['requests' => RequestService::all()]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        $req = RequestService::query()->find($id);
        if ($req->employee_ids[0] != null)
            $req->handyman = User::query()->find($req->employee_ids[0])->simplifiedArray();
        $req->client = User::query()->find($req->client_ids[0])->simplifiedArray();


        return view('cms.requests.index', compact('req'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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


    public function destroy($id)
    {
        try {
            RequestService::query()->find($id)->delete();
        } catch (\Exception $e) {
        }

        return redirect()->route('request.index');
    }


}
