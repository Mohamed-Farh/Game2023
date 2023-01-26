<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PriceRequest;
use App\Http\Requests\Backend\ShopRequest;
use App\Models\HundredGame;
use App\Models\Price;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_shop,show_shop')) {
            return redirect('admin/index');
        }

        $shops = Shop::when(\request()->keyword !=null, function($query){
            $query->search(\request()->keyword);
        })
            ->when(\request()->active !=null, function($query){
                $query->whereActive(\request()->active);
            })
            ->orderBy(\request()->sort_by ?? 'id' ,  \request()->order_by ?? 'desc')

            ->paginate(\request()->limit_by ?? 15);

        return view('backend.shops.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_shop,create_shop')) {
            return redirect('admin/index');
        }
        return view('backend.shops.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\HundredGame\StoreHundredGameRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShopRequest $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_shop,create_shop')) {
            return redirect('admin/index');
        }

        do{
            $code = mt_rand(1111111111,9999999999);
            $is_code = Shop::where('code', $code)->get();
        }
        while(!$is_code->isEmpty());

        $shop = Shop::create([
            'name' => $request->name,
            'win_tokens' => $request->win_tokens,
            'cost' => $request->cost,
            'code' => $code,
            'start_time' => $request->start,
            'end_time' => $request->end,
            'active' => $request->active,
        ]);

        if ($image = $request->file('image')) {
            $filename = time().'.'.$image->getClientOriginalExtension();
            $path = ('images/shop/' . $filename);
            Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
        }
        $shop->update([
            'image' => $path,
        ]);

        Alert::success('تم انشاء العرض بنجاح', 'Success Message');
        return redirect(route('admin.shops.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shop $shop
     * @return \Illuminate\Http\Response
     */
    public function show(Shop $shop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shop $shop
     * @return \Illuminate\Http\Response
     */
    public function edit(Shop $shop)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_shop,edit_shop')) {
            return redirect('admin/index');
        }
        $shop = Shop::findOrFail($shop->id);
        return view('backend.shops.edit', compact('shop'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ShopRequest  $request
     * @param  \App\Models\Shop $shop
     * @return \Illuminate\Http\Response
     */
    public function update(ShopRequest $request, Shop $shop)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_shop,edit_shop')) {
            return redirect('admin/index');
        }
        // Shop data
        $shop->update([
            'name' => $request->name,
            'win_tokens' => $request->win_tokens,
            'cost' => $request->free == 1 ? 0 : $request->cost,
            'start_time' => $request->start,
            'end_time' => $request->end,
            'active' => $request->active,
            'free' => $request->free,
        ]);

        if ($image = $request->file('image'))
        {
            if ($shop->image != null && File::exists($shop->image)) {
                unlink($shop->image);
            }
            $filename = time().'.'.$image->getClientOriginalExtension();
            $path = ('images/shop/' . $filename);
            Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $shop->update([ 'image' => $path, ]);
        }
        Alert::success('تم تعديل العرض', 'Success Message');
        return redirect(route('admin.shops.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shop $shop
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shop $shop)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_shop,edit_shop')) {
            return redirect('admin/index');
        }
        $shop = Shop::findOrFail($shop->id);

        if (File::exists($shop->image)) :
            unlink($shop->image);
        endif;
        $shop->delete();

        Alert::success('تم حذف العرض بنجاح', 'Success Message');
        return redirect(route('admin.shops.index'));
    }

    public function removeImage(Request $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_shop,edit_shop')) {
            return redirect('admin/index');
        }
        $shop = Shop::whereId($request->shop_id)->first();
        if ($shop) {
            if (File::exists( $shop->image)) {
                unlink( $shop->image);
                $shop->image = null;
                $shop->save();
            }
        }
        return true;
    }
    public function changeStatus(Request $request)
    {
        if (!\auth()->user()->ability('superAdmin', 'manage_shop,edit_shop')) {
            return redirect('admin/index');
        }

        $shop = Shop::whereId($request->cat_id)->first();
        $shop->active = $request->status;
        $shop->save();
        return response()->json(['success'=>'Status Change Successfully.']);
    }
    public function massDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $shop = Shop::findorfail($id);
            if (File::exists($shop->image)) :
                unlink($shop->image);
            endif;
            $shop->delete();
        }
        return response()->json([
            'error' => false,
        ], 200);
    }
}
