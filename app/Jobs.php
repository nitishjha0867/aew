<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    //
	protected $fillable = [
		'section', 'make', 'product_item_code', 'description', 'product_quantity', 'product_rate', 'discount', 'due_date', 'delivery_date'
	];

	public function ofOrder(){
		return $this->belongsTo('App\Order', 'order_id');
	}
}
