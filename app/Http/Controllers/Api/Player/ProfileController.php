<?php

namespace App\Http\Controllers\Api\Player;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SendTokenToPlayerRequest;
use App\Http\Requests\Api\UpdateProfileRequest;
use App\Http\Resources\Profile\MyPricesTableResource;
use App\Http\Resources\Profile\PriceResource;
use App\Http\Resources\Profile\TransactionResource;
use App\Http\Resources\UserResource;
use App\Models\PlayerPrice;
use App\Models\Price;
use App\Models\Transition;
use App\Models\User;
use App\Models\UserAddress;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;


class ProfileController extends Controller
{
    use GeneralTrait;

    public function sendTokenToPlayer(SendTokenToPlayerRequest $request)
    {
        /** take token from player **/
        $player = User::whereId(\auth()->id())->first();
        if($player->token_amount < $request->token_amount ){
            return $this->successMessage(0,'Your Token Balance Is Not Enough');
        }

        if($request->id != ''){
            $recive_player = User::whereId($request->id)->first();
        }
        if($request->username != ''){
            $recive_player = User::whereUsername($request->username)->first();
        }
        $player->update([ 'token_amount' => ($player->token_amount) - ($request->token_amount) ]);
        $recive_player->update([ 'token_amount' => ($recive_player->token_amount) + ($request->token_amount) ]);

        $transition['sender_id']    = \auth()->id();
        $transition['receiver_id']  = $recive_player->id;
        $transition['amount']       = $request->token_amount;
        $transition['created_at']   = now();
        Transition::create($transition);

        return $this->successMessage(1, 'The Token Was Sent Successfully');
    }

    public function senderTransactions(Request $request)
    {
        $myTransitions = Transition::whereSenderId(\auth()->id())->latest('id')->get();
        return $this->successMessage( TransactionResource::collection($myTransitions), 'My Sender Transitions');
    }
    public function receiverTransactions(Request $request)
    {
        $myTransitions = Transition::whereReceiverId(\auth()->id())->latest('id')->get();
        return $this->successMessage( TransactionResource::collection($myTransitions), 'My Receiver Transitions');
    }

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
