<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\HundredGameRequest;
use App\Models\GamePlayer;
use App\Models\HundredGame;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;

class HundredGameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_hundred_games,show_hundred_games')) {
            return redirect('admin/index');
        }
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
        if (!\auth()->user()->ability('superAdmin', 'manage_hundred_games,create_hundred_games')) {
            return redirect('admin/index');
        }
        return view('backend.hundred-games.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\HundredGame\StoreHundredGameRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HundredGameRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_hundred_games,create_hundred_games')) {
            return redirect('admin/index');
        }
        if(isset($request ->win_numbers)){
            if(count(explode(',', $request->win_numbers)) != $request->no_of_win_numbers){
                Alert::error('عدد الارقام الفائزه لا يتناسب مع الارقام المدخلة', 'Error Message');
                return redirect()->back();
            }
        }

        DB::beginTransaction();
        try {
            // Game data
            $input['no_of_win_numbers'] = $request->no_of_win_numbers;
//            $input['win_numbers']       = $request->win_numbers;
            $input['win_numbers']       = '['.$request->win_numbers.']';
            $input['timer']             = $request->timer;
            $input['start']             = $request->start;
            $input['end']               = $request->end;
            $game = HundredGame::create($input);

            if ($image = $request->file('image')) {
                $filename = time().'.'.$image->getClientOriginalExtension();
                $path = ('images/hundredGame/' . $filename);
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
            return redirect(route('admin.hundred-games.index'));

        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
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
        if (!\auth()->user()->ability('superAdmin', 'manage_hundred_games,show_hundred_games')) {
            return redirect('admin/index');
        }
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
        if (!\auth()->user()->ability('superAdmin', 'manage_hundred_games,update_hundred_games')) {
            return redirect('admin/index');
        }
        return view('backend.hundred-games.edit', compact('hundredGame'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\HundredGame\UpdateHundredGameRequest  $request
     * @param  \App\Models\HundredGame  $hundredGame
     * @return \Illuminate\Http\Response
     */
    public function update(HundredGameRequest $request, HundredGame $hundredGame)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_hundred_games,update_hundred_games')) {
            return redirect('admin/index');
        }
        if(isset($request ->win_numbers)){
            if(count(explode(',', $request->win_numbers)) != $request->no_of_win_numbers){
                Alert::error('عدد الارقام الفائزه لا يتناسب مع الارقام المدخلة', 'Error Message');
                return redirect()->back();
            }
        }

        DB::beginTransaction();
        try {

            // Game data
            $hundredGame->update([
                'no_of_win_numbers' => $request->no_of_win_numbers,
                'win_numbers' => explode(',', $request->win_numbers),
                'timer' => $request->timer,
                'start' => $request->start,
                'end' => $request->end,
                'active' => $request->active,
            ]);

            if ($image = $request->file('image')) {
                if ($hundredGame->image != null && File::exists( $hundredGame->image )) {
                    unlink( $hundredGame->image );
                }
                $filename = time().'.'.$image->getClientOriginalExtension();
                $path = ('images/hundredGame/' . $filename);
                Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);
                $hundredGame->update([
                    'image' => $path,
                ]);
            }

            // Price data
            $hundredGame->basicPrice()->update([
                'name' => $request->price_name,
                'description' => $request->price_description,
                'value' => $request->price_value,
                'start_time' => $request->start,
                'end_time' => $request->end,
            ]);
            if ($price_image = $request->file('price_image')) {
                if ($hundredGame->basicPrice()->image != null && File::exists( $hundredGame->basicPrice()->image )) {
                    unlink( $hundredGame->basicPrice()->image );
                }
                $filename = time().'.'.$price_image->getClientOriginalExtension();
                $path = ('images/price/' . $filename);
                Image::make($price_image->getRealPath())->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);
                $hundredGame->basicPrice()->update([
                    'image' => $path,
                ]);
            }

            DB::commit(); // insert data
            Alert::success('تم تحديث اللعبة بنجاح', 'Success Message');
            return redirect(route('admin.hundred-games.index'));

        }catch (\Exception $e){
            DB::rollback();
            Alert::success($e->getMessage(), 'Success Message');
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HundredGame  $hundredGame
     * @return \Illuminate\Http\Response
     */
    public function destroy(HundredGame $hundredGame)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_hundred_games,delete_hundred_games')) {
            return redirect('admin/index');
        }
        $game_player = GamePlayer::where('game_id', $hundredGame->id)->where('game_type', 'hundred')->first();
        if($game_player){
            Alert::alert('لا يمكن حذف هذه اللعبة لوجود لاعبين بها', 'Alert Message');
            return redirect(route('admin.hundred-games.index'));
        }

        foreach ($hundredGame->prices as $price) {
            if ($price->image != null && File::exists( $price->image )) {
                unlink( $price->image );
            }
            $price->delete();
        }

        if ($hundredGame->image != null && File::exists( $hundredGame->image )) {
            unlink( $hundredGame->image );
        }
        $hundredGame->delete();

        Alert::success('تم حذف اللعبه و الالعاب الخاصة بها بنجاح', 'Success Message');
        return redirect(route('admin.hundred-games.index'));
    }

    public function changeStatus(Request $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_hundred_games,update_hundred_games')) {
            return redirect('admin/index');
        }
        $hundredGame = HundredGame::whereId($request->cat_id)->first();
        $hundredGame->active = $request->active;
        $hundredGame->save();

        return response()->json(['success'=>'تم تغيير الحالة بنجاح']);
    }
    public function massDestroy(Request $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_hundred_games,delete_hundred_games')) {
            return redirect('admin/index');
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            $hundredGame = HundredGame::findorfail($id);
            if (File::exists($hundredGame->image)) :
                unlink($hundredGame->image);
            endif;
            $hundredGame->delete();
        }
        return response()->json([
            'error' => false,
        ], 200);
    }
}
