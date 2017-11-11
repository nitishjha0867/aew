<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'enquiry';
	
	//primary key of table
	protected $primaryKey = "sr_no";
	
	protected $guarded = [];
	
	public function addAttachments(){
		return $this->hasMany('App\Attachments', 'attachment_id');
	}
	
	public function addProducts(){
		return $this->hasMany('App\Products', 'product_id');
	}

        public function addOrder(){
		return $this->belongsTo('App\Order', 'order_id');
	}
	
}
