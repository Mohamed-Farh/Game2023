<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class InformationController extends Controller
{
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_informations')) {
            return redirect('admin/index');
        }
        $informations = Information::get();
        return view('backend.informations.index', compact('informations'));
    }

    public function create()
    {
        //
    }

    public function store(Information $request)
    {
        //
    }


    public function show($id)
    {
        //
    }

    public function edit(Information $information)
    {
        return view('backend.informations.edit', compact('information'));
    }

    public function update(Request $request, Information $information)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_informations')) {
            return redirect('admin/index');
        }
        $input['text']     = $request->text;
        $information->update($input);

        Alert::success('Data Updated Successfully', 'Success Message');
        return redirect()->route('admin.informations.index');
    }

    public function destroy(Information $informations)
    {
        //

    }

}

