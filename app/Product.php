<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
use Vinkla\Hashids\Facades\Hashids;
// use Hashids;


class Product extends Eloquent
{
    public function attributes()
    {
    	return $this->hasMany('App\ProductsAttribute','product_id');
    }

 // protected $hidden   = array('id');
 //    protected $appends = array('token');

    public function getTokenAttribute()
    {
        return Hashids::encode($this->id);
    }

}