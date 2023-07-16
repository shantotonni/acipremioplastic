<?php

use App\Model\Product;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'ProjectID'=>1,
            'ProductCodeSystem'=>'',
            'Business'=>'',
            'CategoryId'=>1,
            'SubCategoryId'=>1,
            'ProductName'=>'ACI Aroma Basmati Kernel Rice 1 KG',
            'ProductDetails'=>'ACI Aroma Basmati Kernel Rice 1 KG',
            'ProductMetaData'=>'ACI Aroma Basmati Kernel Rice 1 KG',
            'ProductImageFileName'=>'food_2.png',
            'ItemPrice'=>120,
            'DiscountType'=>'',
            'Discount'=>20,
            'ItemFinalPrice'=>100,
            'CreatedBy'=>1,
            'CreatedDate'=>'',
            'CreatedIP'=>'',
            'CreatedDeviceState'=>'',
            'EditedBy'=>'',
            'EditedDate'=>'',
            'EditedIP'=>'',
            'EditedDeviceState'=>'',
        ]);

        Product::create([
            'ProjectID'=>1,
            'ProductCodeSystem'=>'',
            'Business'=>'',
            'CategoryId'=>2,
            'SubCategoryId'=>2,
            'ProductName'=>'ACI Aroma Aromatic Rice 1 kg',
            'ProductDetails'=>'ACI Aroma Aromatic Rice 1 kg',
            'ProductMetaData'=>'ACI Aroma Aromatic Rice 1 kg',
            'ProductImageFileName'=>'food_3.png',
            'ItemPrice'=>120,
            'DiscountType'=>'',
            'Discount'=>20,
            'ItemFinalPrice'=>100,
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
