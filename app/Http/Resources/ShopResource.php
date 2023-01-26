<?php

namespace App\Http\Resources;

use App\Models\GamePlayer;
use App\Models\Price;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->free == 1){
            $default_image =  env('APP_URL').'images/icon/free_gift.png';
        }else{
            $default_image =  env('APP_URL').'images/icon/gift.png';
        }

        return [

            "id" => $this->id ?? '',
            "name" => $this->name ?? '',
            "code" => $this->code ?? '',
            "win_tokens" => $this->win_tokens ?? '',
            "cost" => $this->cost ?? '',
            "image" => isset($this->image) ? env('APP_URL').$this->image : $default_image,
            "active" => (boolean) $this->active,
            "free" => (boolean) $this->free,
            "created_at" => $this->created_at ?? '',
            "updated_at" => $this->updated_at ?? '',

        ];
    }
}
