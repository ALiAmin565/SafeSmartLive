<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecommendationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return
        [
            'id'=>$this->id,
            'title'=>$this->title,
            'desc'=>$this->desc,
            'currency'=>$this->currency,
            'entry_price'=>$this->entry_price,
            'stop_price'=>$this->stop_price,
            'img'=>$this->img,
            'active'=>$this->active,
            'number_show'=>$this->number_show,
            'planes_id '=>$this->planes_id ,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'user'=>UserNameResource::make(($this->whenLoaded('user'))),
            'ViewsRecomenditionnumber'=>count($this->ViewsRecomenditionnumber),
            'target'=>Recommindation_targetResource::collection($this->whenLoaded('target')),
            'plan'=>PlanWithRecomindation::collection($this->whenLoaded('Recommindation_Plan')),
            'tragetsRecmo'=>$this->tragetsRecmo

        ];

    }
}
