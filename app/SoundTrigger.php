<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SoundTrigger extends Model
{
    public function sound()
    {
        return $this->belongsTo('App\Sound');
    }
}
