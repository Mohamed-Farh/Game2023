<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Price\StorePriceRequest;
use App\Http\Requests\Price\UpdatePriceRequest;
use App\Models\Player;
use App\Models\Transition;
use App\Models\Price;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Transition\StoreTransitionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePriceRequest $request)
    {
       //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transition  $transition
     * @return \Illuminate\Http\Response
     */
    public function show(Transition $transition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transition  $transition
     * @return \Illuminate\Http\Response
     */
    public function edit(Price $price)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Transition\UpdateTransitionRequest  $request
     * @param  \App\Models\Transition  $transition
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePriceRequest $request, Price $price)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transition  $transition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Price $price)
    {
        //
    }

    //-----------------------------------------------------------------------
    public function showExportTransitions(Request $request)
    {
        Carbon::setLocale('ar');
        $player = User::whereId($request->player_id)->first();
        $transitions = $player->exportTransitions;
        return view('backend.transitions.transitions', compact('transitions', 'player'));
    }

    public function showImportTransitions(Request $request)
    {
        Carbon::setLocale('ar');
        $player = User::whereId($request->player_id)->first();
        $transitions = $player->importTransitions;
        return view('backend.transitions.transitions', compact('transitions', 'player'));
    }
}
