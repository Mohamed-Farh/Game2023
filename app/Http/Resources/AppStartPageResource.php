<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppStartPageResource extends JsonResource
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
            "text" => isset($this->text) ? $this->text : '',
            "image" => isset($this->image) ? env('APP_URL').$this->image : '',
            "number" => isset($this->number) ? $this->number : '',
        ];
    }
}
