<?php

use App\Model\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'ProjectID'=>1,
            'Category'=>'Food and Commodities',
            'CategoryIcon'=>'',
            'CategoryImage'=>'',
            'CategoryDescription'=>'Food and Commodities',
            'CategoryMetaData'=>'Food and Commodities',
            'CategoryStatus'=>'Y',
            'CreatedBy'=>1,
            'CreatedDate'=>'',
            'CreatedIP'=>'',
            'CreatedDeviceState'=>'',
            'EditedBy'=>'',
            'EditedDate'=>'',
            'EditedIP'=>'',
            'EditedDeviceState'=>'',
        ]);

        Category::create([
            'ProjectID'=>1,
            'Category'=>'Personal Care',
            'CategoryIcon'=>'',
            'CategoryImage'=>'',
            'CategoryDescription'=>'Personal Care',
            'CategoryMetaData'=>'Personal Care',
            'CategoryStatus'=>'Y',
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
