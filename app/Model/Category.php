<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'Category';

    protected $primaryKey = 'CategoryId';

    public $timestamps = false;

    public function subcategory(){
        return $this->hasMany('App\Model\SubCategory', 'CategoryId','CategoryId')->where('SubCategoryStatus','Y');
    }

    public function products(){
        return $this->hasMany('App\Model\Product', 'CategoryId','CategoryId')
            ->orderBy('ItemFinalPrice', 'desc')->where('ProductStatus','Y');
    }

    public function onlyHomePageProduct(){
        return $this->products()->take(12);
    }
}
