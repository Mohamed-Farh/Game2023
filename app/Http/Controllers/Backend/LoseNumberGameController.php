<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\LoseNumberGameRequest;
use App\Models\LoseNumberGame;
use App\Models\GamePlayer;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;

class LoseNumberGameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_lose_number_games,show_lose_number_games')) {
            return redirect('admin/index');
        }
        $games = LoseNumberGame::latest()->get();
        return view('backend.lose-number-games.index', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_lose_number_games,create_lose_number_games')) {
            return redirect('admin/index');
        }
        return view('backend.lose-number-games.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoseNumberGameRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_lose_number_games,create_lose_number_games')) {
            return redirect('admin/index');
        }
        DB::beginTransaction();
        try {
            // Game data
            $input['lose_number']       = $request->lose_number;
            $input['timer']             = $request->timer;
            $input['start']             = $request->start;
            $input['end']               = $request->end;
            $game = LoseNumberGame::create($input);

            if ($image = $request->file('image')) {
                $filename = time().'.'.$image->getClientOriginalExtension();
                $path = ('images/loseNumberGame/' . $filename);
                Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);
                $input['image']  = $path;
                $game->update([
                    'image' => $path,
                ]);
            }

            // Price data
            do{
                $code = mt_rand(1111111111,9999999999);
                $is_code = Price::where('code', $code)->get();
            }
            while(!$is_code->isEmpty());

            $price = Price::create([
                'game_id' => $game->id,
                'game_type' => 'loseNumber',
                'name' => $request->price_name,
                'description' => $request->price_description,
                'value' => $request->price_value,
                'code' => $code,
                'start_time' => $request->start,
                'end_time' => $request->end,
                'win_tokens' => $request->win_tokens ?? 0,
                'basic' => 1,
            ]);

            if ($price_image = $request->file('price_image')) {
                $filename = time().'.'.$price_image->getClientOriginalExtension();
                $path = ('images/price/' . $filename);
                Image::make($price_image->getRealPath())->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);
                $price->update([
                    'image' => $path,
                ]);
            }

            DB::commit(); // insert data
            Alert::success('تم اضافة اللعبة بنجاح', 'Success Message');
            return redirect(route('admin.lose-number-games.index'));

        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LoseNumberGame  $loseNumberGame
     * @return \Illuminate\Http\Response
     */
    public function show(LoseNumberGame $loseNumberGame)
    {
        //
    }

    public function showPrices(LoseNumberGame $loseNumberGame)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_lose_number_games,show_lose_number_games')) {
            return redirect('admin/index');
        }
        $prices = $loseNumberGame->prices;
        return view('backend.lose-number-games.prices.index', compact('loseNumberGame', 'prices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LoseNumberGame  $loseNumberGame
     * @return \Illuminate\Http\Response
     */
    public function edit(LoseNumberGame $loseNumberGame)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_lose_number_games,update_lose_number_games')) {
            return redirect('admin/index');
        }
        return view('backend.lose-number-games.edit', compact('loseNumberGame'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LoseNumberGame  $loseNumberGame
     * @return \Illuminate\Http\Response
     */
    public function update(LoseNumberGameRequest $request, LoseNumberGame $loseNumberGame)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_lose_number_games,update_lose_number_games')) {
            return redirect('admin/index');
        }

        DB::beginTransaction();
        try {

            // Game data
            $loseNumberGame->update([
                'no_of_lose_number' => $request->no_of_lose_number,
                'lose_number' => $request->lose_number,
                'timer' => $request->timer,
                'start' => $request->start,
                'end' => $request->end,
                'active' => $request->active,
            ]);

            if ($image = $request->file('image')) {
                if ($loseNumberGame->image != null && File::exists( $loseNumberGame->image )) {
                    unlink( $loseNumberGame->image );
                }
                $filename = time().'.'.$image->getClientOriginalExtension();
                $path = ('images/loseNumberGame/' . $filename);
                Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);
                $loseNumberGame->update([
                    'image' => $path,
                ]);
            }

            // Price data
            $loseNumberGame->basicPrice()->update([
                'name' => $request->price_name,
                'description' => $request->price_description,
                'value' => $request->price_value,
                'start_time' => $request->start,
                'end_time' => $request->end,
                'win_tokens' => $request->win_tokens ?? 0,
            ]);
            if ($price_image = $request->file('price_image')) {
                if ($loseNumberGame->basicPrice()->image != null && File::exists( $loseNumberGame->basicPrice()->image )) {
                    unlink( $loseNumberGame->basicPrice()->image );
                }
                $filename = time().'.'.$price_image->getClientOriginalExtension();
                $path = ('images/price/' . $filename);
                Image::make($price_image->getRealPath())->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);
                $loseNumberGame->basicPrice()->update([
                    'image' => $path,
                ]);
            }

            DB::commit(); // insert data
            Alert::success('تم تحديث اللعبة بنجاح', 'Success Message');
            return redirect(route('admin.lose-number-games.index'));

        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LoseNumberGame  $loseNumberGame
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoseNumberGame $loseNumberGame)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_lose_number_games,delete_lose_number_games')) {
            return redirect('admin/index');
        }
        $game_player = GamePlayer::where('game_id', $loseNumberGame->id)->where('game_type', 'loseNumber')->first();
        if($game_player){
            Alert::alert('لا يمكن حذف هذه اللعبة لوجود لاعبين بها', 'Alert Message');
            return redirect(route('admin.lose-number-games.index'));
        }

        foreach ($loseNumberGame->prices as $price) {
            if ($price->image != null && File::exists( $price->image )) {
                unlink( $price->image );
            }
            $price->delete();
        }

        if ($loseNumberGame->image != null && File::exists( $loseNumberGame->image )) {
            unlink( $loseNumberGame->image );
        }
        $loseNumberGame->delete();

        Alert::success('تم حذف اللعبه و الالعاب الخاصة بها بنجاح', 'Success Message');
        return redirect(route('admin.lose-number-games.index'));
    }

    public function changeStatus(Request $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_lose_number_games,update_lose_number_games')) {
            return redirect('admin/index');
        }
        $loseNumberGame = LoseNumberGame::whereId($request->cat_id)->first();
        $loseNumberGame->active = $request->active;
        $loseNumberGame->save();

        return response()->json(['success'=>'تم تغيير الحالة بنجاح']);
    }
    public function massDestroy(Request $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_lose_number_games,delete_lose_number_games')) {
            return redirect('admin/index');
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            $loseNumberGame = LoseNumberGame::findorfail($id);
            if (File::exists($loseNumberGame->image)) :
                unlink($loseNumberGame->image);
            endif;
            $loseNumberGame->delete();
        }
        return response()->json([
            'error' => false,
        ], 200);
    }
}
