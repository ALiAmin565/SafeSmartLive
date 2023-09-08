<?php

namespace App\Http\Resources\Front;

use App\Http\Resources\RecommendationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ArchiveResource extends JsonResource
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
            'titile'=>$this->title,
            'desc'=>$this->desc,


        ];
    }
}
