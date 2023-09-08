<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'active'=>$this->active,
            'limit'=>$this->limit,
            'used'=>$this->used,
            'code'=>$this->code,
            'user_id'=>$this->user_id,
            'plan_id'=>$this->plan_id,
        ];
    }
}
