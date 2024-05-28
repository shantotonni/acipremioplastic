<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFeatures extends Model
{
    protected $fillable = [
        'user_id', 'feature_id', 'admin_id',
    ];

    public function user() {
      return $this->belongs_to('User');
    }

    public function feature() {
      return $this->belongs_to('Feature');
    }
}
