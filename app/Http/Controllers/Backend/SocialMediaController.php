<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CategoryRequest;
use App\Http\Requests\Backend\LogoRequest;
use App\Http\Requests\Backend\SocialMediaRequest;
use App\Models\Category;
use App\Models\Logo;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class SocialMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contacts,show_socials')) {
            return redirect('admin/index');
        }

        $socials = SocialMedia::orderBy('id', 'desc')->paginate(10);

        return view('backend.socials.index', compact('socials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contacts,show_socials')) {
            return redirect('admin/index');
        }

        return view('backend.socials.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SocialMediaRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contacts,show_socials')) {
            return redirect('admin/index');
        }

        $input['type']      = $request->type;
        $input['link']      = $request->link;
        $input['status']    = $request->status;

        SocialMedia::create($input);

        Alert::success('Social Media Created Successfully', 'Success Message');
        return redirect()->route('admin.socials.index');

    }


    public function show($id)
    {
        //
    }

    public function edit(SocialMedia $social)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contacts,show_socials')) {
            return redirect('admin/index');
        }

        return view('backend.socials.edit', compact('social'));
    }

    public function update(SocialMediaRequest $request, SocialMedia $social)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contacts,show_socials')) {
            return redirect('admin/index');
        }

        $input['type']      = $request->type;
        $input['link']      = $request->link;
        $input['status']    = $request->status;

        $social->update($input);

        Alert::success('Social Media Updated Successfully', 'Success Message');
        return redirect()->route('admin.socials.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SocialMedia $social)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contacts,show_socials')) {
            return redirect('admin/index');
        }

        $social->delete();

        Alert::success('Social Media Deleted Successfully', 'Success Message');
        return redirect()->route('admin.socials.index');

    }


    public function massDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $social = SocialMedia::findorfail($id);
            $social->delete();
        }
        return response()->json([
            'error' => false,
        ], 200);

    }

    public function changeStatus(Request $request)
    {
        $social = SocialMedia::find($request->cat_id);
        $social->status = $request->status;
        $social->save();
        return response()->json(['success'=>'Status Change Successfully.']);
    }


}

