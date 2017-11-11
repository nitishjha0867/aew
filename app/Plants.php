<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plants extends Model
{
    // table name & its primary key
	protected $table = "plants";
	protected $primaryKey = "plant_id";

	// allows to insert/update below mentioned fields at once
    protected $fillable = [
        'plant_name', 'plant_address', 'plant_state', 'plant_city'
    ];
    /* surporisingly above fields are getting insterted even when they are not mentioned in $fillable*/

    public function ofClient(){
    	return $this->belongsTo('App\Clients', 'plant_id');
    }

    public function addContactPersons(){
    	return $this->hasMany('App\ClientsContact', 'plant_id');
    }
}
