<?php

namespace App\Http\Resources;

use App\Models\GamePlayer;
use App\Models\Price;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerPriceResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $price = Price::whereId($this->price_id)->first();
        $gamePlayer  = GamePlayer::whereId($this->game_player_id)->first();

        return [
            "id" => $this->id ?? '',

            //****Price
            "price_id" => $this->price_id ?? '',
            "price_name" => isset($this->price_id) && $price->name != '' ? $price->name : '',
            "price_description" => isset($this->price_id) && $price->description != '' ? $price->description : '',
            "price_image" => isset($this->price_id) && $price->image != '' ? env('APP_URL').$price->image : env('APP_URL').'images/app_image.png',

            //****Game
            "game_id" => $gamePlayer->game_id ?? '',
            "game_type" => $gamePlayer->game_type ?? '',
            "win_numbers" => $gamePlayer->numbers ?? '',
            "timer" => $gamePlayer->timer ?? '',

            "active" => (boolean) $this->active,
            "send" => (boolean) $this->basic,
            "deliverd" => (boolean) $this->deliverd,
            "created_at" => $this->created_at ?? '',
            "updated_at" => $this->updated_at ?? ''
        ];
    }
}
