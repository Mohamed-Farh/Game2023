<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AboutRequest;
use App\Models\About;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_abouts,show_abouts')) {
            return redirect('admin/index');
        }

        $abouts = About::orderBy('id', 'desc')->paginate(10);

        return view('backend.abouts.index', compact('abouts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_abouts,show_abouts')) {
            return redirect('admin/index');
        }

        return view('backend.abouts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AboutRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_abouts,show_abouts')) {
            return redirect('admin/index');
        }

        $input['about']      = $request->about;
        About::create($input);

        Alert::success('About Created Successfully', 'Success Message');
        return redirect()->route('admin.abouts.index');

    }


    public function show($id)
    {
        //
    }

    public function edit(About $about)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_abouts,show_abouts')) {
            return redirect('admin/index');
        }

        return view('backend.abouts.edit', compact('about'));
    }

    public function update(AboutRequest $request, About $about)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_abouts,show_abouts')) {
            return redirect('admin/index');
        }

        $input['about']      = $request->about;
        $about->update($input);

        Alert::success('About Updated Successfully', 'Success Message');
        return redirect()->route('admin.abouts.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(About $about)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_abouts,show_abouts')) {
            return redirect('admin/index');
        }

        $about->delete();

        Alert::success('About Deleted Successfully', 'Success Message');
        return redirect()->route('admin.abouts.index');

    }

}

