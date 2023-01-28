<?php

namespace App\Http\Resources;

use App\Models\LoseNumberGame;
use Illuminate\Http\Resources\Json\JsonResource;

class LoseNumberGameDetailsResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $currentLoseNumberGame = LoseNumberGame::whereId($this->id)->first();
        $currentPrice = $currentLoseNumberGame->currentPrice() ?? $currentLoseNumberGame->basicPrice();


        return [
            "id" => $this->id,
            "lose_number" => $this->lose_number,
            "timer" => $this->timer ?? '',
            "start" => $this->start,
            "end" => $this->end,
            "image" => isset($this->image) ? env('APP_URL').$this->image : env('APP_URL').'images/app_image.png',
            "active" => (boolean) $this->active,
            "price_details" => new PriceResource($currentPrice),
        ];
    }
}
