<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientsContact extends Model
{
    // table name & its primary key
	protected $table = "clients_contact";
	protected $primaryKey = "contact_id";

	// allows to insert/update below mentioned fields at once
    // protected $fillable = [
    //     'client_name'
    // ];

	public function forPlant(){
		/* relationship set to belongsToMany since
		 * since one contact person may belong to
		 * multiple plants of a client
		 * if this is NOT the case, change it to belongsTo
		 */
		return $this->belongsTo('App\Plants', 'contact_id');
	}

}
