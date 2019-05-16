<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    //
    public function map() {
    	return $this->belongsTo('App\Map');
    }

    public function stairMarkerOrigin() {
    	return $this->hasOne('App\Marker', 'connecting_marker_id');
    }

    public function stairMarkerTarget() {
    	return $this->belongsTo('App\Marker', 'connecting_marker_id');
    }
}
