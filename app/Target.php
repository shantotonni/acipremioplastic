<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Target extends Model{
  
    protected $fillable=["date","area_id","territory_id","technitian_id","tractor_warranty",
    "tractor_post_warranty","nm_warranty","nm_post_warranty",
    "warranty_service","post_warranty_service","installation",
    "preodic_service","post_warranty_visit","total",
    'warranty_master_total','post_warranty_master_total',
    "note","engineer_id","creator_id","updater_id",
    "service_income","tractor_spare_parts_lubricants",
    "nm_pt_spare_parts_lubricants"
  
  ];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function territory(){
      return $this->belongsTo('App\Territory');
    }
    public function technitian(){
      return $this->belongsTo('App\User');
    }
    public function engineer(){
      return $this->belongsTo('App\User');
    }
    public function creator(){
      return $this->belongsTo('App\User');
    }
    public function updater(){
      return $this->belongsTo('App\User');
    }
    public function area(){
      return $this->belongsTo('App\Area');
    }

}
