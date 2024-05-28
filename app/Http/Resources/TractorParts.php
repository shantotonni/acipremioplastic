<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TractorParts extends JsonResource
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
            'name' =>$this->custom_name,
            'section_id' =>$this->section_id,
            'section' => isset($this->section) ? $this->section->name : '',
            'trade_pirce' =>isset($this->product) ? $this->product->UnitPrice: 0,
            'stock' =>0,
            'image' =>url('/part_image').'/'.$this->image,
        ];
    }
}
