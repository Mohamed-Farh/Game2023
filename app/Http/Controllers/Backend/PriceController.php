<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PriceRequest;
use App\Models\HundredGame;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;

class PriceController extends Controller
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
        $hundredGame = HundredGame::findOrFail($request->hundredGame);
        return view('backend.prices.create', compact('hundredGame'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\HundredGame\StoreHundredGameRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PriceRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_price,create_price')) {
            return redirect('admin/index');
        }
        $hundredGame = HundredGame::findOrFail($request->hundredGame);

        // Price data
        do{
            $code = mt_rand(1111111111,9999999999);
            $is_code = Price::where('code', $code)->get();
        }
        while(!$is_code->isEmpty());

        $price = Price::create([
            'game_id' => $hundredGame->id,
            'game_type' => 'hundred',
            'name' => $request->price_name,
            'description' => $request->price_description,
            'value' => $request->price_value,
            'code' => $code,
            'start_time' => $request->start,
            'end_time' => $request->end,
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

        Alert::success('Price Created Successfully', 'Success Message');
        return redirect(route('admin.hundred-games.showPrices', $hundredGame->id));
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HundredGame  $hundredGame
     * @return \Illuminate\Http\Response
     */
    public function edit(Price $price)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_price,edit_price')) {
            return redirect('admin/index');
        }
        $hundredGame = HundredGame::findOrFail($price->game_id);
        return view('backend.prices.edit', compact('price', 'hundredGame'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\HundredGame\UpdateHundredGameRequest  $request
     * @param  \App\Models\HundredGame  $hundredGame
     * @return \Illuminate\Http\Response
     */
    public function update(PriceRequest $request, Price $price)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_price,edit_price')) {
            return redirect('admin/index');
        }
        // Price data
        $price->update([
            'name' => $request->price_name,
            'description' => $request->price_description,
            'value' => $request->price_value,
            'start_time' => $request->start,
            'end_time' => $request->end,
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
//        toastr()->success('تم تعديل الجائزة');
        Alert::success('Price Updated Successfully', 'Success Message');
        return redirect(route('admin.hundred-games.showPrices', $price->game_id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HundredGame  $hundredGame
     * @return \Illuminate\Http\Response
     */
    public function destroy(Price $price)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_price,edit_price')) {
            return redirect('admin/index');
        }
        $hundredGame = HundredGame::findOrFail($price->game_id);

        if (File::exists($price->image)) :
            unlink($price->image);
        endif;
        $price->delete();

        Alert::success('Price Deleted Successfully', 'Success Message');
        return redirect(route('admin.hundred-games.showPrices', $hundredGame->id));
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
