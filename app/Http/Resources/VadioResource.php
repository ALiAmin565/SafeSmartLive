<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VadioResource extends JsonResource
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
            'title' => $this->title,
            'img' => $this->img,
            'desc' => $this->desc,
            'video' => $this->video,
            'video_link' => $this->video_link,
        ];
    }
}
