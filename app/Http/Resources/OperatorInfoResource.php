<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OperatorInfoResource extends JsonResource
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
            'operator_name' =>$this->operator_name,
            'village' =>$this->village,
            'post_office' =>$this->post_office,
            'police_station' =>$this->police_station,
            'area' =>isset($this->area_name) ? $this->area_name->name: '',
            'district' =>isset($this->district_name) ? $this->district_name->name: '',
            'mobile' =>$this->mobile,
            'training_level' => $this->training_level,
            'training_date' => $this->training_date ? date('d-m-Y', strtotime($this->training_date)) : null,
            'training_venue' => $this->training_venue,
            'total_training_days' => $this->total_training_days,
            'operating_experience' => $this->operating_experience,
            'education' => $this->education,
            'nid_no' => $this->nid_no,
            'image_url' => url('operator_images/').'/'.$this->image_url,
            'created_at' => $this->created_at ? date('d-m-Y H:i:s',strtotime($this->created_at)) : null,
        ];
    }
}
