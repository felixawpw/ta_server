<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    //
    public function maps() {
    	return $this->hasMany("App\Map");
    }

    public function user() {
    	return $this->belongsTo("App\User");
    }

    public function markers() {
    	return $this->hasManyThrough('App\Marker', 'App\Map');
    }
}
