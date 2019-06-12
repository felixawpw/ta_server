<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    public function tenant() {
    	return $this->belongsTo("App\Tenant");
    }

    public function marker() {
    	return $this->belongsTo("App\Marker");
    }
}
