<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\LocationRequest;
use App\Models\Location;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_locations')) {
            return redirect('admin/index');
        }
        $locations = Location::orderBy('id', 'desc')->paginate(10);
        return view('backend.locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_locations')) {
            return redirect('admin/index');
        }
        return view('backend.locations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocationRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_locations')) {
            return redirect('admin/index');
        }
        $input['country_id']    = $request->country_id;
        $input['state_id']      = $request->state_id;
        $input['city_id']       = $request->city_id;
        $input['description']    = $request->description;
        $input['location']    = $request->location;
        $input['status']    = $request->status;

        Location::create($input);
        Alert::success('Location Created Successfully', 'Success Message');
        return redirect()->route('admin.locations.index');
    }


    public function show($id)
    {
        //
    }

    public function edit(Location $location)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_locations')) {
            return redirect('admin/index');
        }
        return view('backend.locations.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LocationRequest $request, Location $location)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_locations')) {
            return redirect('admin/index');
        }
        $input['country_id']    = $request->country_id;
        $input['state_id']      = $request->state_id;
        $input['city_id']       = $request->city_id;
        $input['description']    = $request->description;
        $input['location']    = $request->location;
        $input['status']    = $request->status;

        $location->update($input);
        Alert::success('Location Updated Successfully', 'Success Message');
        return redirect()->route('admin.locations.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_locations')) {
            return redirect('admin/index');
        }
        $location->delete();
        Alert::success('Location Deleted Successfully', 'Success Message');
        return redirect()->route('admin.locations.index');
    }

    public function changeStatus(Request $request)
    {
        $location = Location::find($request->cat_id);
        $location->status = $request->status;
        $location->save();
        return response()->json(['success'=>'Status Change Successfully.']);
    }

}

