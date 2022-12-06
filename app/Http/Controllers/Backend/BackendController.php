<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;


class BackendController extends Controller
{
    public function index()
    {
        return view('backend.index');
    }

    public function login()
    {
        return view('backend.login');
    }

    public function forget_password()
    {
        return view('backend.forgot-password');
    }

    public function get_state(Request $request)
    {
        $states = State::whereCountryId($request->country_id)->whereStatus(true)->get(['id', 'name'])->toArray();

        return response()->json($states);
    }


    public function get_city(Request $request)
    {
        $cities = City::whereStateId($request->state_id)->whereStatus(true)->get(['id', 'name'])->toArray();

        return response()->json($cities);
    }
}
