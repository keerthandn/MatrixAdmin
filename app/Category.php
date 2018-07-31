<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
use Vinkla\Hashids\Facades\Hashids;


class Category extends Eloquent
{
    public function categories()
    {
    	return $this->hasMany('App\Category','parent_id');
    }

    public function getTokenAttribute()
    {
        return Hashids::encode($this->id);
    }
}
