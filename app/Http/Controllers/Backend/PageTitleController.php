<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PageTitleRequest;
use App\Models\PageTitle;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PageTitleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_page_title')) {
            return redirect('admin/index');
        }
        $pageTitles = PageTitle::orderBy('id', 'desc')->paginate(10);
        return view('backend.page-titles.index', compact('pageTitles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_page_title')) {
            return redirect('admin/index');
        }

        return view('backend.page-titles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PageTitleRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_page_title')) {
            return redirect('admin/index');
        }

        $input['title']     = $request->title;
        $input['page']      = $request->page;
        $input['status']    = $request->status;

        PageTitle::create($input);
        Alert::success('PageTitle Created Successfully', 'Success Message');
        return redirect()->route('admin.page-titles.index');
    }


    public function show($id)
    {
        //
    }

    public function edit(PageTitle $pageTitle)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_page_title')) {
            return redirect('admin/index');
        }
        return view('backend.page-titles.edit', compact('pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PageTitleRequest $request, PageTitle $pageTitle)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_page_title')) {
            return redirect('admin/index');
        }

        $input['title']     = $request->title;
        $input['page']      = $request->page;
        $input['status']    = $request->status;

        $pageTitle->update($input);
        Alert::success('PageTitle Updated Successfully', 'Success Message');
        return redirect()->route('admin.page-titles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PageTitle $pageTitle)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_page_title')) {
            return redirect('admin/index');
        }

        $pageTitle->delete();
        Alert::success('PageTitle Deleted Successfully', 'Success Message');
        return redirect()->route('admin.page-titles.index');

    }

    public function changeStatus(Request $request)
    {
        $pageTitle = PageTitle::find($request->cat_id);
        $pageTitle->status = $request->status;
        $pageTitle->save();
        return response()->json(['success'=>'Status Change Successfully.']);
    }

}

