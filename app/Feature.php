<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    public function userFearures() {
      return $this->has_many('userFearures');
    }
}
