<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'Customer';
    public $timestamps = false;
    protected $primaryKey = 'CustomerID';

    protected $fillable = [
        'CustomerFirstName','CustomerLastName','CustomerMobileNo','CustomerEmail','CreatedDate','CreatedIP','CreatedDeviceState','password','Status',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function customerAddress(){
        return $this->hasMany('App\Model\CustomerAddress', 'CustomerID','CustomerID');
    }

    public function customerAddressTwo(){
        return $this->hasOne('App\Model\CustomerAddress', 'CustomerID','CustomerID')->orderBy('AddressID','desc');
    }

}
