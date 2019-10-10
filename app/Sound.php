<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sound extends Model
{
    public function trigger()
    {
        return $this->hasOne('App\SoundTrigger');
    }
}
