<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TractorParts extends Model
{
    protected $table = 'tractor_parts';
    protected $fillable=["id","code","custom_name","harvester_id",'image','section_id','price'];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function section(){
        return $this->belongsTo('App\Section');
    }

    public function productModel()
    {
        return $this->belongsTo(ProductModel::class, 'product_model_id', 'id');
    }

    public function product(){
        return $this->belongsTo(MotorSparePartsMirrorProduct::class,'ProductCode','ProductCode');
    }
}
