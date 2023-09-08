<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use App\Http\Resources\PlanNameResource;
use App\Http\Resources\UserNameResource;
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
            'id'=>$this->id,
            'title'=>$this->title,
            'desc'=>$this->desc,
            'create_at'=>$this->created_at,
            'recomindation'=>RecommendationResource::make(($this->whenLoaded('recommendation'))),
            'user'=>UserNameResource::make($this->whenLoaded('user')),
            'plan2'=>PlanNameResource::make(($this->plan2)), // Remove the whenLoaded methods


        ];
    }
}
