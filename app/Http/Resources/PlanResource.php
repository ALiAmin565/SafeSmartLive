<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'nameChannel'=>$this->nameChannel,
            'name'=>$this->name,
            'discount'=>$this->discount,
            'price'=>$this->price,
            'percentage1'=>$this->percentage1,
            'percentage2'=>$this->percentage2,
            'percentage3'=>$this->percentage3,
            'plan_desc'=>plane_descResource::collection($this->whenLoaded('plan_desc')),
            'telegram_groups' => TelegremRsource::collection($this->whenLoaded('telegram')),

        ];
    }
}
