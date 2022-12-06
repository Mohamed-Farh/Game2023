<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\WorkingTimeRequest;
use App\Models\WorkingTime;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class WorkingTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_working_times')) {
            return redirect('admin/index');
        }
        $workingTimes = WorkingTime::orderBy('id', 'desc')->paginate(10);
        return view('backend.working_times.index', compact('workingTimes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_working_times')) {
            return redirect('admin/index');
        }

        return view('backend.working_times.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WorkingTimeRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_working_times')) {
            return redirect('admin/index');
        }
// dd($request);
        $input['day']     = $request->day;
        $input['start']      = $request->start;
        $input['end']      = $request->end;
        $input['status']    = $request->status;

        WorkingTime::create($input);
        Alert::success('WorkingTime Created Successfully', 'Success Message');
        return redirect()->route('admin.working_times.index');
    }


    public function show($id)
    {
        //
    }

    public function edit(WorkingTime $workingTime)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_working_times')) {
            return redirect('admin/index');
        }
        return view('backend.working_times.edit', compact('workingTime'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WorkingTimeRequest $request, WorkingTime $workingTime)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_working_times')) {
            return redirect('admin/index');
        }

        $input['day']     = $request->day;
        $input['start']      = $request->start;
        $input['end']      = $request->end;
        $input['status']    = $request->status;

        $workingTime->update($input);
        Alert::success('WorkingTime Updated Successfully', 'Success Message');
        return redirect()->route('admin.working_times.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkingTime $workingTime)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_settings,show_working_times')) {
            return redirect('admin/index');
        }

        $workingTime->delete();
        Alert::success('WorkingTime Deleted Successfully', 'Success Message');
        return redirect()->route('admin.working_times.index');

    }

    public function changeStatus(Request $request)
    {
        $workingTime = WorkingTime::find($request->cat_id);
        $workingTime->status = $request->status;
        $workingTime->save();
        return response()->json(['success'=>'Status Change Successfully.']);
    }

}

