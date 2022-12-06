<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PlayerResource extends JsonResource
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
            "first_name" => isset($this->first_name) ? $this->first_name : '',
            "last_name" => isset($this->last_name) ? $this->last_name : '',
            "username" => isset($this->username) ? $this->username : '',
            "full_name" => isset($this->full_name) ? $this->full_name : '',
            "email" => isset($this->email) ? $this->email : '',
            "mobile" => isset($this->mobile) ? $this->mobile : '',
            "token_amount" => isset($this->token_amount) ? $this->token_amount : '',
            "user_image" => $this->user_image != '' ? env('APP_URL').$this->user_image : env('APP_URL').'images/user/avatar.png',
            "active" => (boolean)($this->active),
            "account_status" => (boolean)($this->account_status),
            "mobile_verify" => (boolean)($this->mobile_verify),
            "created_at" => strval($this->created_at),
            "updated_at" => strval($this->updated_at),
        ];
    }
}
