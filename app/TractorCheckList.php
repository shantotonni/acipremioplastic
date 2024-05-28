<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TractorCheckList extends Model
{
    protected $table = 'tractor_check_list';
    protected $guarded = [];
    protected $primaryKey = 'id';
}
