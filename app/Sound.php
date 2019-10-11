<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sound extends Model
{
   	public function sound_triggers()
    {
        return $this->hasMany('App\SoundTrigger');
    }
}
