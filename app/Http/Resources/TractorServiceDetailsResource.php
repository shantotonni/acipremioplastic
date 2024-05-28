<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TractorServiceDetailsResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' =>$this->id,
            'section_name' => $this->topic->section->name,
            'parts_name' =>$this->topic->name,
            'fixed_hr' =>$this->fixed_hr,
            'servicing_type' =>$this->servicing_type->name,
        ];
    }
}
