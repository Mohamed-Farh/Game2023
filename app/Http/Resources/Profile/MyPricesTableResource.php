<?php

namespace App\Http\Resources\Profile;

use App\Models\GamePlayer;
use App\Models\Price;
use Illuminate\Http\Resources\Json\JsonResource;

class MyPricesTableResource extends JsonResource
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
        $gamePlayer = GamePlayer::whereId($this->game_player_id)->first();


        return [
            "id" => $this->id ?? '',
            "name" => $price->name ?? '',
            "code" => $price->code ?? '',
            "game_type" => $gamePlayer->game_type,
            "win_numbers" => $gamePlayer->numbers,
            "image" => isset($this->image) ? env('APP_URL').$price->image : env('APP_URL').'images/app_image.png',
            "send" => (boolean) $this->send,
            "delivered" => (boolean) $this->delivered,
            "active" => (boolean) $this->active,
            "created_at" => $this->created_at ?? '',
            "updated_at" => $this->updated_at ?? '',
        ];
    }
}
