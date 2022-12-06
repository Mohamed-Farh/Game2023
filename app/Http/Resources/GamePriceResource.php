<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GamePriceResource extends JsonResource
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
            "game_id" => $this->game_id ?? '',
            "game_type" => $this->game_type ?? '',
            "name" => $this->name ?? '',
            "description" => $this->game_id ?? '',
            "code" => $this->code ?? '',
            "value" => $this->value ?? '',
            "timer" => $this->timer ?? '',
            "start_time" => $this->start_time,
            "end_time" => $this->end_time,
            "image" => isset($this->image) ? env('APP_URL').$this->image : env('APP_URL').'images/app_image.png',
            "active" => (boolean) $this->active,
            "basic" => (boolean) $this->basic,
        ];
    }
}
