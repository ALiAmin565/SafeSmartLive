<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'id' => $this->id,
            'image_payment' => $this->image_payment,
            'status' => $this->status,
            'transaction_id'=>$this->transaction_id,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'plan'=>PlanNameResource::make($this->whenLoaded('plan')),
            'user'=>UserNameResource::make($this->whenLoaded('user'))
        ];
    }
}
