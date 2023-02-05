<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PriceRequest;
use App\Models\LoseNumberGame;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;

class LoseNumberGamePriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_price,show_price')) {
            return redirect('admin/index');
        }

        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_price,create_price')) {
            return redirect('admin/index');
        }
        $loseNumberGame = LoseNumberGame::findOrFail($request->loseNumberGame);
        return view('backend.lose-number-games.prices.create', compact('loseNumberGame'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LoseNumberGame\StoreLoseNumberGameRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PriceRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_price,create_price')) {
            return redirect('admin/index');
        }
        $loseNumberGame = LoseNumberGame::findOrFail($request->loseNumberGame);

        // Price data
        do{
            $code = mt_rand(1111111111,9999999999);
            $is_code = Price::where('code', $code)->get();
        }
        while(!$is_code->isEmpty());

        $price = Price::create([
            'game_id' => $loseNumberGame->id,
            'game_type' => 'loseNumber',
            'name' => $request->price_name,
            'description' => $request->price_description,
            'value' => $request->price_value,
            'code' => $code,
            'start_time' => $request->start,
            'end_time' => $request->end,
            'win_tokens' => $request->win_tokens ?? 0,
        ]);

        if ($price_image = $request->file('price_image')) {
            $filename = time().'.'.$price_image->getClientOriginalExtension();
            $path = ('images/price/' . $filename);
            Image::make($price_image->getRealPath())->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
        }
        $price->update([
            'image' => $path,
        ]);

        Alert::success('تم اضافة الجائزة', 'Success Message');
        return redirect(route('admin.lose-number-games.showPrices', $loseNumberGame->id));
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LoseNumberGame  $loseNumberGame
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Price $price)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_price,edit_price')) {
            return redirect('admin/index');
        }
        $price = Price::findOrFail($request->price_id);
        $loseNumberGame = LoseNumberGame::findOrFail($price->game_id);
        return view('backend.lose-number-games.prices.edit', compact('price', 'loseNumberGame'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LoseNumberGame\UpdateLoseNumberGameRequest  $request
     * @param  \App\Models\LoseNumberGame  $loseNumberGame
     * @return \Illuminate\Http\Response
     */
    public function update(PriceRequest $request, Price $price)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_price,edit_price')) {
            return redirect('admin/index');
        }
        $price = Price::findOrFail($request->price_id);
        // Price data
        $price->update([
            'name' => $request->price_name,
            'description' => $request->price_description,
            'value' => $request->price_value,
            'start_time' => $request->start,
            'end_time' => $request->end,
            'win_tokens' => $request->win_tokens ?? 0,
            'active' => $request->active,
        ]);

        if ($price_image = $request->file('price_image'))
        {
            if ($price->image != null && File::exists($price->image)) {
                unlink($price->image);
            }
            $filename = time().'.'.$price_image->getClientOriginalExtension();
            $path = ('images/price/' . $filename);
            Image::make($price_image->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $price->update([ 'image' => $path, ]);
        }
        Alert::success('تم تعديل الجائزة', 'Success Message');
        return redirect(route('admin.lose-number-games.showPrices', $price->game_id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LoseNumberGame  $loseNumberGame
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Price $price)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_price,edit_price')) {
            return redirect('admin/index');
        }
        $price = Price::findOrFail($request->price_id);
        $loseNumberGame = LoseNumberGame::findOrFail($price->game_id);

        if (File::exists($price->image)) :
            unlink($price->image);
        endif;
        $price->delete();

        Alert::success('تم حذف الجائزة', 'Success Message');
        return redirect(route('admin.lose-number-games.showPrices', $loseNumberGame->id));
    }

    public function removeImage(Request $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_price,edit_price')) {
            return redirect('admin/index');
        }
        $price = Price::whereId($request->price_id)->first();
        if ($price) {
            if (File::exists( $price->image)) {
                unlink( $price->image);
                $price->image = null;
                $price->save();
            }
        }
        return true;
    }
    public function changeStatus(Request $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_price,edit_price')) {
            return redirect('admin/index');
        }

        $price = Price::whereId($request->cat_id)->first();
        $price->active = $request->status;
        $price->save();
        return response()->json(['success'=>'Status Change Successfully.']);
    }
    public function massDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $price = Price::findorfail($id);
            if (File::exists($price->image)) :
                unlink($price->image);
            endif;
            $price->delete();
        }
        return response()->json([
            'error' => false,
        ], 200);
    }
}
