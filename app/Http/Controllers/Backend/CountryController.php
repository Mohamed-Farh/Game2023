<?php

namespace App\Http\Controllers\Backend;

use App\Models\Country;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CountryRequest;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_countries,show_countries')) {
            return redirect('admin/index');
        }

        $countries = Country::with('states')

        ->when(\request()->keyword !=null, function($query){
            $query->search(\request()->keyword);
        })
        ->when(\request()->status !=null, function($query){
            $query->whereStatus(\request()->status);
        })
        ->orderBy(\request()->sort_by ?? 'id' ,  \request()->order_by ?? 'desc')

        ->paginate(\request()->limit_by ?? 10);

        return view('backend.countries.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_countries,create_countries')) {
            return redirect('admin/index');
        }

        return view('backend.countries.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CountryRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_countries,create_countries')) {
            return redirect('admin/index');
        }

        Country::create($request->validated());

        Alert::success('Country Created Successfully', 'Success Message');
        return redirect()->route('admin.countries.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_countries,display_countries')) {
            return redirect('admin/index');
        }

        return view('backend.countries.show', compact('country'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_countries,update_countries')) {
            return redirect('admin/index');
        }

        return view('backend.countries.edit', compact('country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CountryRequest $request, Country $country)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_countries,update_countries')) {
            return redirect('admin/index');
        }

        $country->update($request->validated());

        Alert::success('Country Updated Successfully', 'Success Message');
        return redirect()->route('admin.countries.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_countries,delete_countries')) {
            return redirect('admin/index');
        }

        $country->delete();

        Alert::success('Country Deleted Successfully', 'Success Message');
        return redirect()->route('admin.countries.index');

    }

    public function massDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $country = Country::findorfail($id);
            $country->delete();
        }
        return response()->json([
            'error' => false,
        ], 200);

    }

    public function changeStatus(Request $request)
    {
        $country = Country::find($request->cat_id);
        $country->status = $request->status;
        $country->save();
        return response()->json(['success'=>'Status Change Successfully.']);
    }

}
