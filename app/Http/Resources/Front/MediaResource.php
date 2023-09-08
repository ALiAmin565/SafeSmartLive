<?php

namespace App\Http\Resources\Front;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
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
            'img'=>$this->img,
            'video'=>$this->video,
            'audio'=>$this->audio,
            'created_at'=>$this->created_at,
        ];
    }
}
