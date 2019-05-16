<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    //
    public function tenant() {
    	return $this->belongsTo('App\Tenant');
    }

    public function markers() {
    	return $this->hasMany('App\Marker');
    }
}
