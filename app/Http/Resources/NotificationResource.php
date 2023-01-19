<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PaginationResource;

class NotificationResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) : Array
    {
        return [
            "id" => $this->id ?? '',
            "type" => $this->type ?? '',
            "content" => $this->content ?? '',
            "icon" => isset($this->icon) ? env('APP_URL').$this->icon : env('APP_URL').'/images/icons/notification.png',
            "read_at" => $this->read_at ?? false,
            "created_at" => $this->created_at ?? '',
            "updated_at" => $this->updated_at ?? '',

//            'pagination' => [
//                'total' => $this->total(),
//                'count' => $this->count(),
//                'per_page' => $this->perPage(),
//                'current_page' => $this->currentPage(),
//                'total_pages' => $this->lastPage()
//            ],

        ];
    }
}
