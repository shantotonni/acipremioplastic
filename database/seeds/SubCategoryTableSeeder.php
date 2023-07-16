<?php

use App\Model\SubCategory;
use Illuminate\Database\Seeder;

class SubCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubCategory::create([
            'ProjectID'=>1,
            'CategoryId'=>1,
            'SubCategory'=>'sub one',
            'SubCategoryIcon'=>'',
            'SubCategoryImage'=>'',
            'SubCategoryDescription'=>'sub one',
            'SubCategoryMetaData'=>'sub one',
            'SubCategoryStatus'=>'Y',
            'CreatedBy'=>1,
            'CreatedDate'=>'',
            'CreatedIP'=>'',
            'CreatedDeviceState'=>'',
            'EditedBy'=>'',
            'EditedDate'=>'',
            'EditedIP'=>'',
            'EditedDeviceState'=>'',
        ]);
        SubCategory::create([
            'ProjectID'=>1,
            'CategoryId'=>2,
            'SubCategory'=>'sub two',
            'SubCategoryIcon'=>'',
            'SubCategoryImage'=>'',
            'SubCategoryDescription'=>'sub two',
            'SubCategoryMetaData'=>'sub two',
            'SubCategoryStatus'=>'Y',
            'CreatedBy'=>1,
            'CreatedDate'=>'',
            'CreatedIP'=>'',
            'CreatedDeviceState'=>'',
            'EditedBy'=>'',
            'EditedDate'=>'',
            'EditedIP'=>'',
            'EditedDeviceState'=>'',
        ]);
    }
}
