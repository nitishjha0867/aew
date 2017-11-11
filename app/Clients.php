<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
	// table name & its primary key
	protected $table = "clients";
	protected $primaryKey = "client_id";

	protected $fillable = [
        'client_name'
    ];

    // use Clients::save()
    // public function addClient($client_name){
    // 	$this->client_name = $client_name;
    // 	$this->save();
    // }

    public function addPlants(){
    	return $this->hasMany('App\Plants', 'client_id');
    }

}
