<?php

namespace App\Http\Resources\Profile;

use App\Models\GamePlayer;
use App\Models\Price;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
{

    protected $player_price;

    public function secondVariable($value){
        $this->player_price = $value;
        return $this;
    }


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id ?? '',
            "name" => $this->name ?? '',
            "description" => $this->description ?? '',
            "code" => $this->code ?? '',
            "value" => $this->value ?? '',
            "win_tokens" => $this->win_tokens ?? '',
            "image" => isset($this->image) ? env('APP_URL').$this->image : env('APP_URL').'images/app_image.png',
            "send" => (boolean) $this->player_price->send,
            "delivered" => (boolean) $this->player_price->delivered,
            "active" => (boolean) $this->player_price->active,
            "created_at" => $this->player_price->created_at ?? '',
            "updated_at" => $this->player_price->updated_at ?? '',
        ];
    }
}
