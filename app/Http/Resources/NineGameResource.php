<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NineGameResource extends JsonResource
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
            "no_of_win_numbers" => $this->no_of_win_numbers,
            "win_numbers" => $this->win_numbers,
            "timer" => $this->timer ?? '',
            "start" => $this->start,
            "end" => $this->end,
            "image" => isset($this->image) ? env('APP_URL').$this->image : env('APP_URL').'images/app_image.png',
            "active" => (boolean) $this->active,
        ];
    }
}
