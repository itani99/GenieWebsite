<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Yajra\DataTables\Contracts\DataTable;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ServiceController extends Controller
{

    public function test(Request $request)
    {
        if ($request->ajax()) {
            $data = Service::query()->with('users')->get();

            return Datatables::of($data)
                ->addIndexColumn()->addColumn('action', function ($row) {

                    $btn = '<a href="' . route('service.edit', $row['id']) . '" class="edit btn btn-primary btn-sm">View</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);

        }

        return view('cms.services.test');
    }

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
        if ($request->has('service_picture')) {
            $file = $request->file('service_picture');
            $name = 'service_' . time() . '.' . $file->getClientOriginalExtension();

            if (!Storage::disk('public')->exists('services')) {
                Storage::disk('public')->makeDirectory('services');
            }


            if (Storage::disk('public')->putFileAs('services', $file, $name)) {
                $service->image = 'services/' . $name;
            } else {
                return redirect()->back();
            }
        }
        $service->name = $request->input('service_name');
        $user_ids = [];
        $service->user_ids = $user_ids;
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


    public function destroy($id)
    {

        try {
            $service=Service::query()->find($id)->delete();

        } catch (\Exception $e) {
        }

        return redirect()->route('service.index');
    }

//    public function index(Request $request)
//    {
//        if ($request->ajax()) {
//            $data = Service::query()->with('users')->get();
//            return DataTables::of($data)
//                ->addColumn('action', function ($data) {
//                    $button = '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-primary btn-sm">Edit</button>';
//                    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" id="' . $data->id . '" class="delete btn btn-danger btn-sm">Delete</button>';
//                    return $button;
//                })
//                ->rawColumns(['action'])
//                ->make(true);
//        }
//        return view('cms.services.test');
//    }
//
//
//    public function create()
//    {
//        //
//    }
//
//
//    public function store(Request $request)
//    {
//        $rules = array(
//            'name' => 'required'
//        );
//
//        $error = Validator::make($request->all(), $rules);
//
//        if ($error->fails()) {
//            return response()->json(['errors' => $error->errors()->all()]);
//        }
//
//        $form_data = array(
//            'name' => $request->name
//
//        );
//
//        Service::create($form_data);
//
//        return response()->json(['success' => 'Data Added successfully.']);
//
//    }
//
//
//    public function show(Service $sample_data)
//    {
//        //
//    }
//
//
//    public function edit($id)
//    {
//        if (request()->ajax()) {
//            $data = Service::findOrFail($id);
//            return response()->json(['result' => $data]);
//        }
//    }
//
//
//    public function update(Request $request, Service $sample_data)
//    {
//        $rules = array(
//            'name' => 'required'
//        );
//
//        $error = Validator::make($request->all(), $rules);
//
//        if ($error->fails()) {
//            return response()->json(['errors' => $error->errors()->all()]);
//        }
//
//        $form_data = array(
//            'name' => $request->name
//        );
//
//        Service::whereId($request->hidden_id)->update($form_data);
//
//        return response()->json(['success' => 'Data is successfully updated']);
//
//    }
//
//    public function destroy($id)
//    {
//        $data = Service::findOrFail($id);
//        $data->delete();
//    }

}
