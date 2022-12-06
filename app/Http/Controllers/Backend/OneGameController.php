<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\HundredGame\StoreHundredGameRequest;
use App\Http\Requests\HundredGame\UpdateHundredGameRequest;
use App\Models\GameType;
use App\Models\HundredGame;
use App\Models\Price;

class OneGameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $games = HundredGame::latest()->get();
        return view('backend.hundred-games.index', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.hundred-games.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\HundredGame\StoreHundredGameRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHundredGameRequest $request)
    {
        // Game data
        $input['no_of_win_numbers'] = $request->no_of_win_numbers;
        $input['win_numbers']       = $request->win_numbers;
        $input['timer']             = $request->timer;
        $input['start']             = $request->start;
        $input['end']               = $request->end;
        $game = HundredGame::create($input);
        if ($image = $request->file('image')) {
            $game->clearMediaCollection();
            $game->addMedia($image->getRealPath())->toMediaCollection();
        }

        // Price data
        do{
            $code = mt_rand(1111111111,9999999999);
            $is_code = Price::where('code', $code)->get();
        }
        while(!$is_code->isEmpty());

        $price = Price::create([
            'game_id' => $game->id,
            'game_type' => 'hundred',
            'name' => $request->price_name,
            'description' => $request->price_description,
            'value' => $request->price_value,
            'code' => $code,
            'start_time' => $request->start,
            'end_time' => $request->end,
            'basic' => 1,
        ]);
        if ($price_image = $request->file('price_image')) {
            $price->clearMediaCollection();
            $price->addMedia($price_image->getRealPath())->toMediaCollection();
        }

        toastr()->success('تم اضافة اللعبة');
        return redirect(route('dashboard.hundred-games.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HundredGame  $hundredGame
     * @return \Illuminate\Http\Response
     */
    public function show(HundredGame $hundredGame)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HundredGame  $hundredGame
     * @return \Illuminate\Http\Response
     */
    public function showPrices(HundredGame $hundredGame)
    {
        $prices = $hundredGame->prices;
        return view('backend.prices.index', compact('hundredGame', 'prices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HundredGame  $hundredGame
     * @return \Illuminate\Http\Response
     */
    public function edit(HundredGame $hundredGame)
    {
        return view('backend.hundred-games.edit', compact('hundredGame'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\HundredGame\UpdateHundredGameRequest  $request
     * @param  \App\Models\HundredGame  $hundredGame
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHundredGameRequest $request, HundredGame $hundredGame)
    {
        // Game data
        $hundredGame->update([
            'no_of_win_numbers' => $request->no_of_win_numbers,
            'win_numbers' => $request->win_numbers,
            'timer' => $request->timer,
            'start' => $request->start,
            'end' => $request->end,
            'active' => $request->active,
        ]);
        if ( $request->file('image') != '') {
            $image = $request->file('image')->getRealPath();
            $hundredGame->clearMediaCollection();
            $hundredGame->addMedia($image)->toMediaCollection();
        }

        // Price data
        $hundredGame->basicPrice()->update([
            'name' => $request->price_name,
            'description' => $request->price_description,
            'value' => $request->price_value,
            'start_time' => $request->start,
            'end_time' => $request->end,
        ]);
        if ( $request->file('price_image') != '') {
            $price_image = $request->file('price_image')->getRealPath();
            $hundredGame->basicPrice()->clearMediaCollection();
            $hundredGame->basicPrice()->addMedia($price_image)->toMediaCollection();
        }

        toastr()->success('تم تعديل اللعبة');
        return redirect(route('dashboard.hundred-games.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HundredGame  $hundredGame
     * @return \Illuminate\Http\Response
     */
    public function destroy(HundredGame $hundredGame)
    {
        //
    }
}
