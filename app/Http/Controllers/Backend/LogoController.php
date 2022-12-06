<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\LogoRequest;
use App\Models\Logo;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LogoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_logos')) {
            return redirect('admin/index');
        }
        $logos = Logo::orderBy('id', 'desc')->paginate(10);
        return view('backend.logos.index', compact('logos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_logos')) {
            return redirect('admin/index');
        }
        return view('backend.logos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LogoRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_logos')) {
            return redirect('admin/index');
        }
        $input['logo']      = $request->logo;
        $input['status']    = $request->status;

        if ($image = $request->file('logo')) {
            $filename = time() . md5(uniqid()) .'.'.$image->getClientOriginalExtension();
            $path = ('images/logo/' . $filename);
            Image::make($image->getRealPath())->resize(450, null, function ($constraint) {
                $constraint->aspectRatio();  //لتنسيق العرض مع الطول
            })->save($path, 100);  //الجودة و درجة الوضوح تكون 100%
            $input['logo']  = $path;
        }
        Logo::create($input);
        Alert::success('Logo Created Successfully', 'Success Message');
        return redirect()->route('admin.logos.index');
    }


    public function show($id)
    {
        //
    }

    public function edit(Logo $logo)
    {
        //
    }

    public function update(LogoRequest $request, Logo $logo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Logo $logo)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_logos')) {
            return redirect('admin/index');
        }

        if ($logo->logo != null && is_file($logo->logo)) {
            unlink($logo->logo);
        }
        $logo->delete();
        Alert::success('Logo Deleted Successfully', 'Success Message');
        return redirect()->route('admin.logos.index');

    }

    public function changeStatus(Request $request)
    {
        $logo = Logo::find($request->cat_id);
        $logo->status = $request->status;
        $logo->save();
        return response()->json(['success'=>'Status Change Successfully.']);
    }
}

