<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoseNumberGameResource extends JsonResource
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
            "id" => $this->id,
            "no_of_win_numbers" => 8,
            "lose_number" => $this->lose_number,
            "timer" => $this->timer ?? '',
            "start" => $this->start,
            "end" => $this->end,
            "image" => isset($this->image) ? env('APP_URL').$this->image : env('APP_URL').'images/app_image.png',
            "active" => (boolean) $this->active,
        ];
    }
}
