<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';
	
	//primary key of table
	protected $primaryKey = "product_id";
	
	protected $guarded = [];
	
	public function ofEnquiry(){
    	return $this->belongsTo('App\Enquiry', 'product_id');
    }
}
