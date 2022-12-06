<?php

namespace App\Http\Resources;

use App\Models\UserAddress;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserInfoResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        ### ِAddress
        $merchant_address = UserAddress::whereUserId(\auth()->id())->first();

        return [
            "id" => $this->id,
            "first_name" => isset($this->first_name) ? $this->first_name : '',
            "last_name" => isset($this->last_name) ? $this->last_name : '',
            "username" => isset($this->username) ? $this->username : '',
            "full_name" => isset($this->full_name) ? $this->full_name : '',
            "email" => isset($this->email) ? $this->email : '',
            "mobile" => isset($this->mobile) ? $this->mobile : '',
            "user_image" => isset($this->user_image) ? env('APP_URL').$this->user_image : '',
            "status" => isset($this->status) ? $this->status : '',
            "account_status" => isset($this->account_status) ? $this->account_status : '',
            "order_status" => isset($this->order_status) ? $this->order_status : '',

            "address" => isset($merchant_address->address) ? $merchant_address->address : '',
            "country_id" => isset($merchant_address->country_id) ? $merchant_address->country_id : '',
            "state_id" => isset($merchant_address->state_id) ? $merchant_address->state_id : '',
            "city_id" => isset($merchant_address->city_id) ? $merchant_address->city_id : '',
            "zip_code" => isset($merchant_address->zip_code) ? $merchant_address->zip_code : '',
            "po_box" => isset($merchant_address->po_box) ? $merchant_address->po_box : '',

        ];
    }
}
