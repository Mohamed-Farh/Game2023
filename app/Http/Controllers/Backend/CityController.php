<?php

namespace App\Http\Controllers\Backend;

use App\Models\City;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CityRequest;
use App\Models\State;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_cities,show_cities')) {
            return redirect('admin/index');
        }

        $cities = City::query()

        ->when(\request()->keyword !=null, function($query){
            $query->search(\request()->keyword);
        })
        ->when(\request()->status !=null, function($query){
            $query->whereStatus(\request()->status);
        })
        ->orderBy(\request()->sort_by ?? 'id' ,  \request()->order_by ?? 'desc')

        ->paginate(\request()->limit_by ?? 10);

        return view('backend.cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_cities,create_cities')) {
            return redirect('admin/index');
        }

        $states = State::get(['id', 'name']);

        return view('backend.cities.create', compact('states'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CityRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_cities,create_cities')) {
            return redirect('admin/index');
        }

        City::create($request->validated());

        Alert::success('City Created Successfully', 'Success Message');
        return redirect()->route('admin.cities.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_cities,show_cities')) {
            return redirect('admin/index');
        }

        return view('backend.cities.show', compact('city'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_cities,update_cities')) {
            return redirect('admin/index');
        }

        $states = State::get(['id', 'name']);

        return view('backend.cities.edit', compact('city', 'states'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CityRequest $request, City $city)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_cities,update_cities')) {
            return redirect('admin/index');
        }

        $city->update($request->validated());

        Alert::success('City Updated Successfully', 'Success Message');
        return redirect()->route('admin.cities.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_cities,delete_cities')) {
            return redirect('admin/index');
        }

        $city->delete();

        Alert::success('City Deleted Successfully', 'Success Message');
        return redirect()->route('admin.cities.index');

    }

    public function massDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $city = City::findorfail($id);
            $city->delete();
        }
        return response()->json([
            'error' => false,
        ], 200);

    }

    public function changeStatus(Request $request)
    {
        $city = City::find($request->cat_id);
        $city->status = $request->status;
        $city->save();
        return response()->json(['success'=>'Status Change Successfully.']);
    }

}
