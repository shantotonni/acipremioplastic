<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMenu extends Model
{
    protected $table = 'UserMenu';
    public $timestamps = false;
    protected $primaryKey = "UserID,MenuID";

    protected $fillable = [
        'UserID',
        'MenuID'
    ];

    public function menu() {
        return $this->belongsTo('\App\Menu','MenuID','MenuID');
    }
}
