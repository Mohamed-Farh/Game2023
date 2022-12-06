<?php

namespace App\Http\Controllers\Backend;

use App\Models\Country;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StateRequest;
use App\Models\State;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_states,show_states')) {
            return redirect('admin/index');
        }

        $states = State::with('cities')

        ->when(\request()->keyword !=null, function($query){
            $query->search(\request()->keyword);
        })
        ->when(\request()->status !=null, function($query){
            $query->whereStatus(\request()->status);
        })
        ->orderBy(\request()->sort_by ?? 'id' ,  \request()->order_by ?? 'desc')

        ->paginate(\request()->limit_by ?? 10);

        return view('backend.states.index', compact('states'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_states,create_states')) {
            return redirect('admin/index');
        }

        $countries = Country::get(['id', 'name']);

        return view('backend.states.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StateRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_states,create_states')) {
            return redirect('admin/index');
        }

        State::create($request->validated());

        Alert::success('State Created Successfully', 'Success Message');
        return redirect()->route('admin.states.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(State $state)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_states,show_states')) {
            return redirect('admin/index');
        }

        return view('backend.states.show', compact('state'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(State $state)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_states,update_states')) {
            return redirect('admin/index');
        }

        $countries = Country::get(['id', 'name']);

        return view('backend.states.edit', compact('state', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StateRequest $request, State $state)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_states,update_states')) {
            return redirect('admin/index');
        }

        $state->update($request->validated());

        Alert::success('State Updated Successfully', 'Success Message');
        return redirect()->route('admin.states.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(State $state)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_states,delete_states')) {
            return redirect('admin/index');
        }

        $state->delete();

        Alert::success('State Deleted Successfully', 'Success Message');
        return redirect()->route('admin.states.index');

    }

    public function massDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $state = State::findorfail($id);
            $state->delete();
        }
        return response()->json([
            'error' => false,
        ], 200);

    }

    public function changeStatus(Request $request)
    {
        $state = State::find($request->cat_id);
        $state->status = $request->status;
        $state->save();
        return response()->json(['success'=>'Status Change Successfully.']);
    }

}
