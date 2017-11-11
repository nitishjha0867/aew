<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachments extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attachments';
	
	//primary key of table
	protected $primaryKey = "attachment_id";
	
	protected $guarded = [];
	
	public function ofEnquiry(){
    	return $this->belongsTo('App\Enquiry', 'attachment_id');
    }
}
