<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Integer;
use Illuminate\Support\Str;

class ServiceController extends Controller
{

    public function index(Request $request)
    {
        $services = Service::all();
        //
        return view('cms.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('cms.services.create');
    }


    public function store(Request $request)
    {
        $service = new Service();
//        $service->image = $this->uploadAny($request->file('service_picture'), 'services');
        if ( $request->has('service_picture')){
            $file = $request->file('service_picture');
            $name = 'service_'. time().$file->extension();

            if ( Storage::disk('public')->exists('services')){
                Storage::disk('public')->makeDirectory('services');
            }

            if ( Storage::disk('public')->put('services/'.$name, $file) ){
                $service->image = 'services/'.$name;
            }else{
                return redirect()->back();
            }
        }
        $service->name = $request->input('service_name');
        $service->save();
        return redirect()->route('service.index');
    }

    public function uploadAny($file, $folder, $ext = 'png')
    {
        /** @var TYPE_NAME $file */
        $file = base64_decode($file);

        /** @var TYPE_NAME $file_name */
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

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return view('cms.services.show');
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

        return view('cms.services.edit');
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        return view('cms.services.index');
    }
}
