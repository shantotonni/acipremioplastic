<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    protected $table = 'Dealers';

    protected $primaryKey = 'DealerId';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    // The attributes that are mass assignable
    protected $fillable = [
        'Name',
        'Phone',
        'DistrictCode',
        'UpazillaCode',
        'Address',
        'Latitude',
        'Longitude',
        'CreatedAt',
        'UpdatedAt'
    ];
}
