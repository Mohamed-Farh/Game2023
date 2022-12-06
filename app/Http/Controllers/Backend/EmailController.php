<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\EmailRequest;
use App\Models\Email;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contacts,show_email')) {
            return redirect('admin/index');
        }

        $emails = Email::orderBy('id', 'desc')->paginate(10);

        return view('backend.emails.index', compact('emails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contacts,show_email')) {
            return redirect('admin/index');
        }

        return view('backend.emails.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmailRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contacts,show_email')) {
            return redirect('admin/index');
        }

        $input['type']      = $request->type;
        $input['email']      = $request->email;
        $input['status']    = $request->status;

        Email::create($input);

        Alert::success('E-Mail Created Successfully', 'Success Message');
        return redirect()->route('admin.emails.index');

    }


    public function show($id)
    {
        //
    }

    public function edit(Email $email)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contacts,show_email')) {
            return redirect('admin/index');
        }

        return view('backend.emails.edit', compact('email'));
    }

    public function update(EmailRequest $request, Email $email)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contacts,show_email')) {
            return redirect('admin/index');
        }

        $input['type']      = $request->type;
        $input['email']      = $request->email;
        $input['status']    = $request->status;

        $email->update($input);

        Alert::success('E-Mail Updated Successfully', 'Success Message');
        return redirect()->route('admin.emails.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Email $email)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_contacts,show_email')) {
            return redirect('admin/index');
        }

        $email->delete();

        Alert::success('E-Mail Deleted Successfully', 'Success Message');
        return redirect()->route('admin.emails.index');

    }


    public function massDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $email = Email::findorfail($id);
            $email->delete();
        }
        return response()->json([
            'error' => false,
        ], 200);

    }

    public function changeStatus(Request $request)
    {
        $email = Email::find($request->cat_id);
        $email->status = $request->status;
        $email->save();
        return response()->json(['success'=>'Status Change Successfully.']);
    }



}

