<?php

namespace App\Http\Resources;

use App\Models\GamePlayer;
use App\Models\Price;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
{

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

            //****Game
            "game_id" => $this->game_id ?? '',
            "game_type" => $this->game_type ?? '',

            "active" => (boolean) $this->active,
            "basic" => (boolean) $this->basic,
            "created_at" => $this->created_at ?? '',
            "updated_at" => $this->updated_at ?? '',

        ];
    }
}
