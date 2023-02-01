<?php

namespace App\Http\Resources\Profile;

use App\Models\GamePlayer;
use App\Models\Price;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
        $sender = User::whereId($this->sender_id)->first();
        $receiver = User::whereId($this->receiver_id)->first();
        return [
            "sender_id" => $this->sender_id ?? '',
            "sender_full_name" => $sender->full_name ?? '',
            "sender_username" => $sender->username  ?? '',

            "receiver_id" => $this->receiver_id ?? '',
            "receiver_full_name" => $receiver->full_name ?? '',
            "receiver_username" => $receiver->username  ?? '',

            "token_amount" => $this->amount ?? '',
            "complete" => (boolean) $this->complete,
            "created_at" => $this->created_at ?? '',
            "updated_at" => $this->updated_at ?? '',
        ];
    }
}
