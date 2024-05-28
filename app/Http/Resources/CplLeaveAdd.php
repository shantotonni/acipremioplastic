<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CplLeaveAdd extends JsonResource{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request){
        //return parent::toArray($request);
        return [
            'id' =>$this->id,
            'employee_id' =>$this->employee_id,
            'worked_on' =>$this->worked_on,
            'instead_of' =>$this->instead_of,
            'first_approver_id' =>$this->first_approver_id,
            'second_approver_id' =>$this->second_approver_id,
            'first_approved_at' =>$this->first_approved_at,
            'first_approved_at' =>$this->first_approved_at,
      ];
    }
}
