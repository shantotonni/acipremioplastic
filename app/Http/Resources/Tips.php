<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Tips extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' =>$this->id,
            'title' =>$this->title,
            'type' =>$this->type,
            'description' =>$this->description,
            'image' => url('/tips_images').'/'.$this->image,
            'video_link' =>$this->video_link,
            'is_active' =>$this->is_active,
        ];
    }
}
