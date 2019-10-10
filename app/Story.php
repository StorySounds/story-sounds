<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    public function sounds()
    {
        return $this->hasMany('App\Sound');
    }
}
