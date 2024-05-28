<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParticipantImage extends Model
{
    protected $table = 'tractor_participant_image';
    protected $guarded = [];
    protected $primaryKey='id';
}
