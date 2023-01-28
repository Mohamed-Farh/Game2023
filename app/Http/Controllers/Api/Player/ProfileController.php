<?php

namespace App\Http\Controllers\Api\Player;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateProfileRequest;
use App\Http\Resources\Profile\MyPricesTableResource;
use App\Http\Resources\Profile\PriceResource;
use App\Http\Resources\UserResource;
use App\Models\PlayerPrice;
use App\Models\Price;
use App\Models\User;
use App\Models\UserAddress;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


class ProfileController extends Controller
{
    use GeneralTrait;

    public function myLatestPrice(Request $request)
    {
        $myLatestPrice = PlayerPrice::whereUserId(\auth()->id())->first();
        $price = Price::whereId($myLatestPrice->price_id)->first();
        return $this->successMessage((new PriceResource($price))->secondVariable($myLatestPrice), 'My Latest Price');
    }

    public function myPricesTable(Request $request)
    {
        $allMyPrices = PlayerPrice::whereUserId(\auth()->id())->get();
        return $this->successMessage(MyPricesTableResource::collection($allMyPrices), 'My Prices');
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $player = User::whereId(\auth()->id())->first();
        $input['first_name']    = $request->first_name ?? $player->first_name;
        $input['last_name']     = $request->last_name ?? $player->last_name;
        $input['username']      = $request->username ?? $player->username;
        $input['mobile']        = $request->mobile ?? $player->mobile;

        if(trim($request->password) != ''){
            $input['password']      = bcrypt($request->password);
        }

        if ($image = $request->file('user_image')) {

            if ($player->user_image != null && File::exists( $player->user_image )) {
                unlink( $player->user_image );
            }

            $filename = time().'.'.$image->getClientOriginalExtension();
            $path = ('images/player/' . $filename);
            Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);
            $input['user_image']  = $path;
        }
        $player->update($input);

        ### ِAddress
        $player_address = UserAddress::whereUserId($player->id)->first();
        if(!empty($player_address)){
            $address['user_id']       = $player->id;
            $address['address']       = $request->address ?? $player->address;
            $player_address->update($address);
        }

        return $this->successMessage(new UserResource($player), 'Your Data Update Successfully');

    }
}
