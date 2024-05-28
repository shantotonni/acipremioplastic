<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TractorDemonstrationRecordEntry extends Model
{
    protected $table = 'tractor_demonstration_record_entry';
    protected $guarded = [];
    protected $primaryKey='id';

    public function participant_info(){
        return $this->hasMany('App\TractorCompititorParticipantInfo','TDREID','id');
    }
    public function trail_report(){
        return $this->hasOne('App\TractorCultivationTrailReport','TDREID','id');
    }

    public function model_image(){
        return $this->hasMany('App\TractorDemonstrationModelImage','TDREID','id');
    }
    public function area(){
        return $this->belongsTo('App\Area','area_id','id');
    }
    public function territory(){
        return $this->belongsTo('App\Territory','territory_id','id');
    }
    public function check_list(){
        return $this->hasMany('App\TractorCheckList','TDREID','id');
    }
    public function participant_image(){
        return $this->hasMany('App\ParticipantImage','TDREID','id');
    }
    public function sales(){
        return $this->hasMany('App\TractorSalesInquiry','TDREID','id');
    }
}
