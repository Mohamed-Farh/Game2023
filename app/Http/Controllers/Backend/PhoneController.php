<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PhoneRequest;
use App\Models\Phone;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PhoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contacts,show_phone')) {
            return redirect('admin/index');
        }

        $phones = Phone::orderBy('id', 'desc')->paginate(10);

        return view('backend.phones.index', compact('phones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contacts,show_phone')) {
            return redirect('admin/index');
        }

        return view('backend.phones.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PhoneRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contacts,show_phone')) {
            return redirect('admin/index');
        }

        $input['type']      = $request->type;
        $input['number']      = $request->number;
        $input['status']    = $request->status;

        Phone::create($input);

        Alert::success('Phone Created Successfully', 'Success Message');
        return redirect()->route('admin.phones.index');

    }


    public function show($id)
    {
        //
    }

    public function edit(Phone $phone)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contacts,show_phone')) {
            return redirect('admin/index');
        }

        return view('backend.phones.edit', compact('phone'));
    }

    public function update(PhoneRequest $request, Phone $phone)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contacts,show_phone')) {
            return redirect('admin/index');
        }

        $input['type']      = $request->type;
        $input['number']      = $request->number;
        $input['status']    = $request->status;

        $phone->update($input);

        Alert::success('Phone Updated Successfully', 'Success Message');
        return redirect()->route('admin.phones.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Phone $phone)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contacts,show_phone')) {
            return redirect('admin/index');
        }

        $phone->delete();

        Alert::success('Phone Deleted Successfully', 'Success Message');
        return redirect()->route('admin.phones.index');

    }


    public function massDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $phone = Phone::findorfail($id);
            $phone->delete();
        }
        return response()->json([
            'error' => false,
        ], 200);

    }

    public function changeStatus(Request $request)
    {
        $phone = Phone::find($request->cat_id);
        $phone->status = $request->status;
        $phone->save();
        return response()->json(['success'=>'Status Change Successfully.']);
    }



}

