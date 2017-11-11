<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Clients;
use App\Plants;
use App\ClientsContact;
use App\Helpers\StringHelper;
use App\Helpers\CsvHelper;
use Alert;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $clients = Clients::all(['client_id', 'client_name']);
        $client_names_arr = $all_states = [];
        foreach($clients as $client){
            $client_names_arr[$client->client_id] = $client->client_name;
        }
        $fetched_states = DB::table('states')->select('state')->where('active', 1)->get();
        foreach($fetched_states as $state){
            $all_states[] = $state->state;
        }
        $clients_index_data = ['all_states'=>$all_states, 'all_clients'=>$client_names_arr];
        return view('manage-clients', $clients_index_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $store = "contactperson", $action = "add")
    {        
		// dd($request->all());
        $data_saved = false;
        $this_action = "";
        switch($store){
            case 'contactperson':
				$data = $request->all();
				$data_arr = array();
				array_push($data_arr, $data);
                $action==="update" ? list($data_saved, $this_action)=array($this->store_reuse($data_arr, true), "updat") : list($data_saved, $this_action)=array($this->store_reuse($data_arr), "sav");
                // if($action==="update"){
                //     $data_saved = $this->store_reuse($data_arr, true);
                //     $this_action = "updat";
                // } else {
                //     $data_saved = $this->store_reuse($data_arr);
                //     $this_action = "sav";
                // }
                break;

            // todo: for developer@nitish
            case 'processcsv':
                $data_arr = array();
                $csv_check = false;
                $i = 0;
                $file = fopen($request->file('clients_csv'),"r");
                while(($ClientData = fgetcsv($file, 10000, ",")) !== FALSE)
                  {
                    if($i != 0)
                    {
                        $data_inner_arr = array();
                        $data_inner_arr['client_name'] = $ClientData[0];
                        $data_inner_arr['plant_name'] = $ClientData[1];
                        $data_inner_arr['plant_state'] = $ClientData[2];
                        $data_inner_arr['plant_city'] = $ClientData[3];
                        $data_inner_arr['plant_address'] = $ClientData[4];
                        $data_inner_arr['cp_count'] = "1";
                        $data_inner_arr['person_name1'] = $ClientData[5];
                        $data_inner_arr['person_designation1'] = $ClientData[6];
                        $data_inner_arr['person_phone1'] = $ClientData[7];
                        $data_inner_arr['person_mobile1'] = $ClientData[8];
                        $data_inner_arr['person_email1'] = $ClientData[9];
                        
                        array_push($data_arr, $data_inner_arr);
                    }else{
                        //dd($ClientData);
                        if(CsvHelper::checkCsv(10, $ClientData)){
                            $csv_check = true;
                        }
                        else{
                            $csv_check = false;
                            break;
                        }
                    }
                    $i++;
                  }
                fclose($file);
                if($csv_check)
                {
                    $data_saved = $this->store_reuse($data_arr);
                }
                else{
                    $data_saved = false;
                }
                $this_action = "upload";
                break;
            
            default: break;
        }
        if(!$data_saved){
            Alert::error('An error occured while '.$this_action.'ing your data. Please try again later.', 'Sorry!')->persistent('Ok');
        } else {
            if($data_saved || $data_saved === "show_alert"){
                Alert::success('Client details '.$this_action.'ed successfully.', 'Success!')->persistent('Ok');
            }
        }
        return redirect()->route('manage-clients.index');
    }
	
	public function store_reuse($data, $update = false)
	{
		// dd($data);
		$all_saved = false;
        if(!$update){
            foreach($data as $request)
            {
                // echo "1--";
                $all_contacts = [];
                $total_contact_persons = StringHelper::sanitize($request['cp_count']);
                for($i=1; $i <= $total_contact_persons; $i++){
                    $clients_contact = new ClientsContact;
                    $clients_contact->person_name = StringHelper::sanitize($request['person_name'.$i], ".");
                    $clients_contact->person_designation = StringHelper::sanitize($request['person_designation'.$i], ".");
                    $clients_contact->person_phone = StringHelper::sanitize($request['person_phone'.$i]);
                    $clients_contact->person_mobile = StringHelper::sanitize($request['person_mobile'.$i], "+");
                    $clients_contact->person_email = trim($request['person_email'.$i]);
                    $all_contacts[] = $clients_contact;
                }
                $client_name = StringHelper::sanitize($request['client_name']);
                $plant_name = StringHelper::sanitize($request['plant_name'], ",.(\")'");
                $existing_client = Clients::where('client_name', $client_name)->value('client_id');
                if(!is_null($existing_client)){
                    // dd("c e");
                    $existing_plant = Plants::where(['client_id'=> $existing_client, 'plant_name'=>$plant_name])->value('plant_id');
                    if(!is_null($existing_plant)){
                        // dd("p e");
                        $all_saved = Plants::findOrFail($existing_plant)->addContactPersons()->saveMany($all_contacts);
                    } else {
                        // dd("p n");
                        $plant_address = StringHelper::sanitize($request['plant_address'], ",.(\")'");
                        $plant_state = StringHelper::sanitize($request['plant_state']);
                        $plant_city = StringHelper::sanitize($request['plant_city']);
                        $all_saved = Clients::findOrFail($existing_client)->addPlants()->create(['plant_name'=>$plant_name, 'plant_address'=>$plant_address, 'plant_state'=>$plant_state, 'plant_city'=>$plant_city])->addContactPersons()->saveMany($all_contacts);
                    }
                } else {
                    // dd("c n");
                    $plant_address = StringHelper::sanitize($request['plant_address'], ",.(\")'");
                    $plant_state = StringHelper::sanitize($request['plant_state']);
                    $plant_city = StringHelper::sanitize($request['plant_city']);
                    $all_saved = Clients::create(['client_name'=>$client_name])->addPlants()->create(['plant_name'=>$plant_name, 'plant_address'=>$plant_address, 'plant_state'=>$plant_state, 'plant_city'=>$plant_city])->addContactPersons()->saveMany($all_contacts);
                }
            }
        } else {
            $error_code = $error = false;
            $client_name = StringHelper::sanitize($data[0]['client_name']);
            $plant_name = StringHelper::sanitize($data[0]['plant_name'], ",.(\")'");
            $existing_client = Clients::where('client_name', $client_name)->value('client_id');
            if(!is_null($existing_client)){
                $existing_plant = Plants::where(['client_id'=> $existing_client, 'plant_name'=>$plant_name])->value('plant_id');
                if(!is_null($existing_plant)){
                    // dd($data[0]);
                    $total_contact_persons = StringHelper::sanitize($data[0]['cp_count']);
                    $new_cids = [];
                    $updated = true;
                    $t = [];
                    for($i=1; $i <= $total_contact_persons; $i++){
                        $new_cid = intval(StringHelper::sanitize($data[0]['contact_id'.$i]));
                        $new_cids[] = $new_cid;
                        $clients_contact = ClientsContact::find($new_cid);
                        $clients_contact->person_name = StringHelper::sanitize($data[0]['person_name'.$i], ".");
                        $clients_contact->person_designation = StringHelper::sanitize($data[0]['person_designation'.$i], ".");
                        $clients_contact->person_phone = StringHelper::sanitize($data[0]['person_phone'.$i]);
                        $clients_contact->person_mobile = StringHelper::sanitize($data[0]['person_mobile'.$i], "+");
                        $clients_contact->person_email = trim($data[0]['person_email'.$i]);
                        // $all_contacts[] = $clients_contact;
                        $others_updated = Plants::findOrFail($existing_plant)->addContactPersons()->where('contact_id', $new_cid)->save($clients_contact);
                        $t[] = $others_updated;
                        if(!$others_updated){
                            $updated = false;
                        }
                    }
                    // delete all old contacts for this plant_id
                    // add new i.e. updated contacts
                    $old_deleted = Plants::findOrFail($existing_plant)->addContactPersons()->select('contact_id')->whereNotIn('contact_id', $new_cids)->delete();
                    // dd($all_contacts);
                    $all_saved = $old_deleted && $updated;
                    // $new=[];$new[]=$old_deleted;$new[]=$updated;
                } else {
                    $error_code = "P404";
                    $error = "Plant '$plant_name' does not exist. Therefore, data updation could not be done.";
                }
            } else {
                $error_code = "C404";
                $error = "Client '$client_name'  does not exist. Therefore, data updation could not be done.";
            }
            if($error_code!==false){
                Alert::error($error, 'Error Code '.$error_code)->persistent('Ok');
            } else {
                return "show_alert";
            }
        }
        // dd($all_saved);
		return $all_saved ? true : false;
	}
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($type, $value)
    {
        //
        if($value!=="" && $value!==null){
            switch($type){
                case 'client1':
                case 'client2':
                    $where_col = $type==="client1" ? 'client_name' : 'client_id';
                    $plants = DB::table('plants')->rightJoin('clients', 'plants.client_id', 'clients.client_id')->select('plant_id', 'plant_name')->where('clients.'.$where_col, $value)->get();
                    if(is_null($plants)){
                        $plants = "no plants found";
                    }
                    return json_encode($plants);
                    break;
                
                case 'plant1':
                case 'plant2':
                    $where_col = $type==="plant1" ? 'plant_name' : 'plant_id';
                    $plant_details = DB::table('plants')->leftJoin('states', 'plants.plant_state', 'states.state')->leftJoin('cities', 'plants.plant_city', 'cities.city')->select('plant_address', 'states.state AS plant_state', 'cities.city AS plant_city')->where('plants.'.$where_col, $value)->first();
                    $contact_details = DB::table('plants')->rightJoin('clients_contact', 'plants.plant_id', 'clients_contact.plant_id')->select('contact_id', 'person_name', 'person_designation', 'person_phone', 'person_mobile', 'person_email')->where('plants.'.$where_col, $value)->get();
                    $action = (!is_null($plant_details) && $type === 'plant1') ? "update" : "add";
                    return json_encode(['action'=> $action, 'plant_details'=>$plant_details, 'contact_details'=>$contact_details]);
                    break;
                
                case 'state1':
                    $states = DB::table('cities')->leftJoin('states', 'cities.state_id', 'states.state_id')->select('city')->where('states.state', $value)->orderBy('city')->get();
                    return json_encode($states);
                    break;
                
                default: break;
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
