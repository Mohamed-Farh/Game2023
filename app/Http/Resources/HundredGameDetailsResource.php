<?php

namespace App\Http\Resources;

use App\Models\HundredGame;
use Illuminate\Http\Resources\Json\JsonResource;

class HundredGameDetailsResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $currentHundredGame = HundredGame::whereId($this->id)->first();
        $currentPrice = $currentHundredGame->currentPrice() ?? $currentHundredGame->basicPrice();


        return [
            "id" => $this->id,
            "no_of_win_numbers" => $this->no_of_win_numbers,
            "timer" => $this->timer ?? '',
            "start" => $this->start,
            "end" => $this->end,
            "image" => isset($this->image) ? env('APP_URL').$this->image : env('APP_URL').'images/app_image.png',
            "active" => (boolean) $this->active,
            "price_details" => new PriceResource($currentPrice),
        ];
    }
}
