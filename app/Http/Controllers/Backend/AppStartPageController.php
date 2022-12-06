<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AppStartPageRequest;
use App\Models\AppStartPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class AppStartPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings')) {
            return redirect('admin/index');
        }

        $appStartPages = AppStartPage::orderBy('number' ,  'asc')->paginate(10);

        return view('backend.appStartPages.index', compact('appStartPages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings')) {
            return redirect('admin/index');
        }

        return view('backend.appStartPages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AppStartPageRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings')) {
            return redirect('admin/index');
        }

        $input['text']           = $request->text;
        $input['number']            = $request->number;
        $input['status']            = $request->status;

        if ($image = $request->file('image')) {
            $filename = Str::slug($request->name).'.'.$image->getClientOriginalExtension();
            $path = ('images/appStartPage/' . $filename);
            Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $input['image']  = $path;
        }
        AppStartPage::create($input);

        Alert::success('AppStartPage Created Successfully', 'Success Message');
        return redirect()->route('admin.appStartPages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AppStartPage $appStartPage)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings')) {
            return redirect('admin/index');
        }

        return view('backend.appStartPages.show', compact('appStartPage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AppStartPage $appStartPage)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings')) {
            return redirect('admin/index');
        }

        return view('backend.appStartPages.edit', compact('appStartPage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AppStartPageRequest $request, AppStartPage $appStartPage)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings')) {
            return redirect('admin/index');
        }

        $input['text']           = $request->text;
        $input['number']            = $request->number;
        $input['status']            = $request->status;

        if ($image = $request->file('image')) {

            if ($appStartPage->image != null && File::exists($appStartPage->image)) {
                unlink($appStartPage->image);
            }

            $filename = Str::slug($request->name).'.'.$image->getClientOriginalExtension();
            $path = ('images/appStartPage/' . $filename);
            Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $input['image']  = $path;
        }

        $appStartPage->update($input);
        Alert::success('AppStartPage Updated Successfully', 'Success Message');
        return redirect()->route('admin.appStartPages.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AppStartPage $appStartPage)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings')) {
            return redirect('admin/index');
        }

        if ($appStartPage->cover != null && File::exists($appStartPage->cover)) {
            unlink($appStartPage->cover);
        }
        $appStartPage->delete();
        Alert::success('AppStartPage Deleted Successfully', 'Success Message');
        return redirect()->route('admin.appStartPages.index');
    }


    public function removeImage(Request $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings')) {
            return redirect('admin/index');
        }

        $appStartPage = AppStartPage::whereId($request->appStartPage_id)->first();
        if ($appStartPage) {
            if (File::exists($appStartPage->cover)) {
                unlink($appStartPage->cover);

                $appStartPage->cover = null;
                $appStartPage->save();
            }
        }
        return true;
    }


    public function massDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $appStartPage = AppStartPage::findorfail($id);
            if (File::exists($appStartPage->cover)) :
                unlink($appStartPage->cover);
            endif;
            $appStartPage->delete();
        }
        return response()->json([
            'error' => false,
        ], 200);

    }

    public function changeStatus(Request $request)
    {
        $appStartPage = AppStartPage::find($request->cat_id);
        $appStartPage->status = $request->status;
        $appStartPage->save();
        return response()->json(['success'=>'Status Change Successfully.']);
    }

}

