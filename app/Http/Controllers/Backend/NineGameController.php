<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\NineGameRequest;
use App\Models\NineGame;
use App\Models\GamePlayer;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;

class NineGameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_nine_games,show_nine_games')) {
            return redirect('admin/index');
        }
        $games = NineGame::latest()->get();
        return view('backend.nine-games.index', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_nine_games,create_nine_games')) {
            return redirect('admin/index');
        }
        return view('backend.nine-games.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NineGameRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_nine_games,create_nine_games')) {
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
            $input['win_numbers']       = explode(',', $request->win_numbers);
            $input['timer']             = $request->timer;
            $input['start']             = $request->start;
            $input['end']               = $request->end;
            $game = NineGame::create($input);

            if ($image = $request->file('image')) {
                $filename = time().'.'.$image->getClientOriginalExtension();
                $path = ('images/nineGame/' . $filename);
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
                'game_type' => 'nine',
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
            return redirect(route('admin.nine-games.index'));

        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NineGame  $nineGame
     * @return \Illuminate\Http\Response
     */
    public function show(NineGame $nineGame)
    {
        //
    }

    public function showPrices(NineGame $nineGame)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_nine_games,show_nine_games')) {
            return redirect('admin/index');
        }
        $prices = $nineGame->prices;
        return view('backend.nine-games.prices.index', compact('nineGame', 'prices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NineGame  $nineGame
     * @return \Illuminate\Http\Response
     */
    public function edit(NineGame $nineGame)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_nine_games,update_nine_games')) {
            return redirect('admin/index');
        }
        return view('backend.nine-games.edit', compact('nineGame'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NineGame  $nineGame
     * @return \Illuminate\Http\Response
     */
    public function update(NineGameRequest $request, NineGame $nineGame)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_nine_games,update_nine_games')) {
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
            $nineGame->update([
                'no_of_win_numbers' => $request->no_of_win_numbers,
                'win_numbers' => explode(',', $request->win_numbers),
                'timer' => $request->timer,
                'start' => $request->start,
                'end' => $request->end,
                'active' => $request->active,
            ]);

            if ($image = $request->file('image')) {
                if ($nineGame->image != null && File::exists( $nineGame->image )) {
                    unlink( $nineGame->image );
                }
                $filename = time().'.'.$image->getClientOriginalExtension();
                $path = ('images/nineGame/' . $filename);
                Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);
                $nineGame->update([
                    'image' => $path,
                ]);
            }

            // Price data
            $nineGame->basicPrice()->update([
                'name' => $request->price_name,
                'description' => $request->price_description,
                'value' => $request->price_value,
                'start_time' => $request->start,
                'end_time' => $request->end,
                'win_tokens' => $request->win_tokens ?? 0,
            ]);
            if ($price_image = $request->file('price_image')) {
                if ($nineGame->basicPrice()->image != null && File::exists( $nineGame->basicPrice()->image )) {
                    unlink( $nineGame->basicPrice()->image );
                }
                $filename = time().'.'.$price_image->getClientOriginalExtension();
                $path = ('images/price/' . $filename);
                Image::make($price_image->getRealPath())->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);
                $nineGame->basicPrice()->update([
                    'image' => $path,
                ]);
            }

            DB::commit(); // insert data
            Alert::success('تم تحديث اللعبة بنجاح', 'Success Message');
            return redirect(route('admin.nine-games.index'));

        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NineGame  $nineGame
     * @return \Illuminate\Http\Response
     */
    public function destroy(NineGame $nineGame)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_nine_games,delete_nine_games')) {
            return redirect('admin/index');
        }
        $game_player = GamePlayer::where('game_id', $nineGame->id)->where('game_type', 'nine')->first();
        if($game_player){
            Alert::alert('لا يمكن حذف هذه اللعبة لوجود لاعبين بها', 'Alert Message');
            return redirect(route('admin.nine-games.index'));
        }

        foreach ($nineGame->prices as $price) {
            if ($price->image != null && File::exists( $price->image )) {
                unlink( $price->image );
            }
            $price->delete();
        }

        if ($nineGame->image != null && File::exists( $nineGame->image )) {
            unlink( $nineGame->image );
        }
        $nineGame->delete();

        Alert::success('تم حذف اللعبه و الالعاب الخاصة بها بنجاح', 'Success Message');
        return redirect(route('admin.nine-games.index'));
    }

    public function changeStatus(Request $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_nine_games,update_nine_games')) {
            return redirect('admin/index');
        }
        $nineGame = NineGame::whereId($request->cat_id)->first();
        $nineGame->active = $request->active;
        $nineGame->save();

        return response()->json(['success'=>'تم تغيير الحالة بنجاح']);
    }
    public function massDestroy(Request $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_nine_games,delete_nine_games')) {
            return redirect('admin/index');
        }
        $ids = $request->ids;
        foreach ($ids as $id) {
            $nineGame = NineGame::findorfail($id);
            if (File::exists($nineGame->image)) :
                unlink($nineGame->image);
            endif;
            $nineGame->delete();
        }
        return response()->json([
            'error' => false,
        ], 200);
    }
}
