<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    public function sound_triggers()
    {
        return $this->belongsToMany('App\SoundTrigger');
    }
}
