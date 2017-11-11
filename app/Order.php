<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
	protected $primaryKey = "order_id";

	protected $fillable = [
		'order_num', 'order_date', 'plant_id'
	];

	public function forEnquiry(){
		return $this->hasMany('App\Enquiry', 'order_id');
	}

	public function addJobs(){
		return $this->hasMany('App\Jobs', 'order_id');
	}

}