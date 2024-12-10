<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'SubCategory';

    public $timestamps = false;

    public function products(){

        return $this->hasMany('App\Model\Product', 'SubCategoryId','SubCategoryId')->where('ProductStatus','Y')->orderBy('ItemPrice','desc');

    }

}
