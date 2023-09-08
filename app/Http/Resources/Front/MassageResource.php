<?php

namespace App\Http\Resources\Front;

use App\Http\Resources\UserNameResource;
use App\Http\Resources\Front\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MassageResource extends JsonResource
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
            'id'=>$this->id,
          'massage' =>$this->massage,
          'created_at'=>$this->created_at,
          'user'=>UserNameResource::make($this->whenLoaded('user')),
          'media'=>MediaResource::make($this->whenLoaded('media'))
        ];
    }
}
