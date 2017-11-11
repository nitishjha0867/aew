<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Clients;
use App\Plants;
use App\ClientsContact;
use App\Enquiry;
use App\Attachments;
use App\Products;
use App\Helpers\StringHelper;
use App\Helpers\IntHelper;
use App\Classes\FPDF\FPDF;
use App\Classes\InvoiceGenerator\InvoiceGenerator;
use Dompdf\Dompdf;
use Alert;

class EnquiryController extends Controller
{
	public $ver_sep = "-";
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
		$unit_data = DB::table('units')->get();
		
		$today_entries = DB::table('enquiry')->count();
        $clients_index_data = ['all_states'=>$all_states, 'all_clients'=>$client_names_arr, 'today_entries'=>$today_entries, 'unit_data'=>$unit_data];
		return view('add-enquiries', $clients_index_data);
		//return $clients_index_data;
		
		//return $this->getID("Bangur Cement", "clients");
		
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
    public function store(Request $request)
    {
		$data = $request->all();
		//var_dump($data);
		// dd($data);
        //
		//$quotation_no = $data['quotation_no'];
		$client_name = StringHelper::sanitize($data['client_name'], ".");
		$plant_name = StringHelper::sanitize($data['plant_name'], ".");
		$enquiry_no = StringHelper::sanitize($data['enquiry_no']);
		// $total_order_value = StringHelper::sanitize($data['total_order_value']);
		//$negotiated_rate = StringHelper::sanitize($data['negotiated_rate'], ".");
		//$previous_rate = StringHelper::sanitize($data['previous_rate'], ".");
		$enquiry_date = StringHelper::sanitize($data['enquiry_date'], ":");
		$due_date = StringHelper::sanitize($data['due_date'], ":");
		$contact_person = StringHelper::sanitize($data['contact_person'], ".");
		
		//getting Id's from name //One can add unit in this as well for csv
		$client_id = $this->getID($client_name, 'clients');
		$has_error = false;
		if($client_id == 'Not found')
		{
			//$client_id = 1;
			$has_error = true;
			Alert::error('Kindly enter the <b>exact</b> name of the Client', 'Unknown Client Name!')->html()->persistent('Ok');
		}
		
		$plant_id = $this->getID($plant_name, 'plants');
		if($plant_id == 'Not found')
		{
			//$plant_id = 1;
			$has_error = true;
			Alert::error('Kindly enter the <b>exact</b> name of the Plant', 'Unknown Plant Name!')->html()->persistent('Ok');
		}
		
		$contact_id = $this->getID($contact_person, 'clients_contact');
		if($contact_id == 'Not found')
		{
			//$contact_id = 1;
			$has_error = true;
			Alert::error('Kindly enter the <b>exact</b> name of the Contact Person', 'Unknown Contact Person Name!')->html()->persistent('Ok');
		}
		if($has_error){ return redirect('/add-enquiry'); }
		
		$p_c = (int)StringHelper::sanitize($data['counter_for_products']);
		$product_item_code = "";
		$product_name = "";
		$product_quantity = "";
		$product_unit = NULL;
		$product_data = array();
		$sim_enq_params = $temp_prod = $temp_draw = $temp_itco = array();
		while($p_c != 0)
		{
			$item_code = isset($data['item_code'.$p_c]) ? StringHelper::sanitize($data['item_code'.$p_c]) : "";
			$product_name = isset($data['product_name'.$p_c]) ? StringHelper::sanitize($data['product_name'.$p_c]) : "";
			$product_quantity = isset($data['product_quantity'.$p_c]) ? StringHelper::sanitize($data['product_quantity'.$p_c]) : "";
			$product_unit = isset($data['product_unit'.$p_c]) ? StringHelper::sanitize($data['product_unit'.$p_c]) : "";
			if(isset($data['product_drawing'.$p_c])){
				if(gettype($data['product_drawing'.$p_c])==="array"){
					$product_drawing = $product_drawing_no = StringHelper::sanitize(implode(",", $data['product_drawing'.$p_c]), ",");
				} else {
					$product_drawing = $product_drawing_no = $data['product_drawing'.$p_c];
				}
			} else {
				$product_drawing = "";
			}
			$temp_itco[] = $item_code;
			$temp_prod[] = $product_name;
			$product_quantity = $product_quantity;
			$product_unit = $product_unit;
			$temp_draw[] = $product_drawing;
			
			$temp_product_data = array("enquiry_no"=>$enquiry_no, "product_name"=>$product_name, "product_item_code"=>$item_code, "product_quantity"=>$product_quantity, "unit_id"=>$product_unit, "drawing_no"=>$product_drawing);
			
			array_push($product_data, $temp_product_data);
			$p_c--;
		}
		
		$sim_enq_params['item_codes'] = $temp_itco;
		// $sim_enq_params['product_names'] = $temp_prod;
		$sim_enq_params['drawing_numbers'] = $temp_draw;
		
		$product_id_string = "";
		foreach($product_data as $data1)
		{
			$product_id = DB::table('products')->insertGetId($data1);
			
			$product_id_string = $product_id_string.$product_id.",";
		}
		
		$product_id_string = rtrim($product_id_string,",");
		
		$counter_drawing = (int)StringHelper::sanitize($data['counter_for_drawing_no']);
		// $counter_costsheet = (int)StringHelper::sanitize($data['counter_for_costsheet']);
		// $counter_aewdrawing = (int)StringHelper::sanitize($data['counter_for_aewdrawing']);
		$client_drawing_path = "documents/".$enquiry_no."/Client Drawing/";
		$cost_sheet_path = "documents/".$enquiry_no."/Cost Sheet/";
		$aew_drawing_path = "documents/".$enquiry_no."/AEW Drawing/";
		$enquiry_mail_path = "documents/".$enquiry_no;
		// make missing directories recursively
		if(!file_exists($client_drawing_path)){
			mkdir($client_drawing_path, 0755, true);
		}
		if(!file_exists($cost_sheet_path)){
			mkdir($cost_sheet_path, 0755, true);
		}
		if(!file_exists($aew_drawing_path)){
			mkdir($aew_drawing_path, 0755, true);
		}
		$client_drawing = "";
		$client_drawing_no = "";
		$cost_sheet = "";
		$aew_drawing = "";
		$enquiry_mail_copy = "";
		if(isset($data['client_drawing_1']))
		{
			while($counter_drawing != 0)
			{
				$imageName = StringHelper::stringReplacecommabyunderscore($request->file('client_drawing_'.$counter_drawing)->getClientOriginalName());
				$temp = $request->file('client_drawing_'.$counter_drawing)->move($client_drawing_path, $imageName);
				$client_drawing .=  $imageName.",";
				$client_drawing_no .= StringHelper::sanitize($data['client_drawing_number_'.$counter_drawing]).",";
				
				$counter_drawing--;
			}
			$client_drawing = rtrim($client_drawing, ',');
			$client_drawing_no = rtrim($client_drawing_no, ',');
		}

		/*if(isset($data['cost_sheet_upload1']))
		{
			while($counter_costsheet != 0)
			{
				$imageName = StringHelper::stringReplacecommabyunderscore($request->file('cost_sheet_upload'.$counter_costsheet)->getClientOriginalName());
				$temp = $request->file('cost_sheet_upload'.$counter_costsheet)->move($cost_sheet_path, $imageName);
				$cost_sheet .=  $imageName.",";
				$counter_costsheet--;
			}
			$cost_sheet = rtrim($cost_sheet, ',');
		}
		
		if(isset($data['aew_drawing_upload1']))
		{
			while($counter_aewdrawing != 0)
			{
				$imageName = StringHelper::stringReplacecommabyunderscore($request->file('aew_drawing_upload'.$counter_aewdrawing)->getClientOriginalName());
				$temp = $request->file('aew_drawing_upload'.$counter_aewdrawing)->move($aew_drawing_path, $imageName);
				$aew_drawing .=  $imageName.",";
				$counter_aewdrawing--;
			}
			$aew_drawing = rtrim($aew_drawing, ',');
		}*/
		
		if(isset($data['enquiry_mail']))
		{
			$imageName = StringHelper::stringReplacecommabyunderscore($request->file('enquiry_mail')->getClientOriginalName());
			$temp = $request->file('enquiry_mail')->move($enquiry_mail_path, $imageName);
			$enquiry_mail_copy = $imageName;
		}
		
		$attachment_data = array("client_drawing_path"=>$client_drawing, "client_drwaing_no"=>$client_drawing_no/*, "cost_sheet_path"=>$cost_sheet, "aew_drawing_path"=>$aew_drawing*/, "enquiry_mail_path"=>$enquiry_mail_copy);
		
		$attachment_id = DB::table('attachments')->insertGetId($attachment_data);
		
		$enquiry_data = array("quotation_no"=>'', "client_id"=>$client_id, "plant_id"=>$plant_id, "enquiry_no"=>$enquiry_no, "attachment_id"=>$attachment_id, "product_id"=>$product_id_string, "enquiry_date"=>$enquiry_date, "due_Date"=>$due_date, "total_order_value"=>'', "negotitated_rate"=>'', "contact_person"=>$contact_id, "lowest_rate"=>'');
		
		$insert_enquiry = DB::table('enquiry')->insertGetId($enquiry_data);
		// $insert_enquiry = true;
		$sim_enq_pag = true;
		if($insert_enquiry){
			// echo "<pre>"; print_r($sim_enq_params); echo "</pre>";
			$similar_enq = $this->findSimilarEnquiry($sim_enq_params, $enquiry_no);
			// dd($similar_enq);
			$similar_enq = json_decode(json_encode($similar_enq), true);
			// echo "<pre>";
			// if(sizeof($similar_enq)>1){
			// 	$sim_enq_pag = true;
			// 	$enquiry_pos = array_search($enquiry_no, $similar_enq);
			// 	unset($similar_enq[$enquiry_pos]);
			// }
			// echo "</pre>";
			Alert::success('Enquiry <b>'.$enquiry_no.'</b> is added successfully', 'Enquiry Added!')->html()->persistent('Ok');
		} else {
			Alert::error('Sorry, could not add the Enquiry <b>'.$enquiry_no.'</b> now. Please try again later.', 'Failure!')->html()->persistent('Ok');
		}
		// dd($similar_enq);
		$sim_enqs_str = "ic:".implode('*', $similar_enq['by_item_code']).":dn:".implode('*', $similar_enq['by_draw_num']);
		return $sim_enq_pag ? redirect('/similar-enquiries/'.$sim_enqs_str.'/'.$enquiry_no) : redirect('/add-enquiry');
    }

    public function findSimilarEnquiry($sim_enq_param, $exclude_enq_no){
    	$res_arr = array();
    	$debug_query = false;
    	if($debug_query){ DB::connection()->enableQueryLog(); }
    	$regex_dr_nos = str_replace(",", "|", $sim_enq_param['drawing_numbers'][0]);
    	// $item_codes_str = implode(",", $sim_enq_param['item_codes']);
    	$res_arr['by_item_code'] = DB::table('products as p')->leftJoin('enquiry as e', 'e.enquiry_no', 'p.enquiry_no')->whereIn('product_item_code', $sim_enq_param['item_codes'])->where('e.enquiry_no', '<>', $exclude_enq_no)->groupBy('e.sr_no')->pluck('e.enquiry_no');
    	$res_arr['by_draw_num'] = DB::table('products as p')->leftJoin('enquiry as e', 'e.enquiry_no', 'p.enquiry_no')->whereraw('drawing_no REGEXP "'.$regex_dr_nos.'"')->where('e.enquiry_no', '<>', $exclude_enq_no)->groupBy('e.sr_no')->pluck('e.enquiry_no');
    	if($debug_query){ $q = DB::getQueryLog(); }
    	// return $q;
    	return $res_arr;
	}

	public function showSimilarEnquiries($sim_enq_nos, $this_enq_num){
		$sim_enq_arr = array();
		$sim_enq_num_arr = explode(":", $sim_enq_nos);
		$sim_by_ic = explode("*", $sim_enq_num_arr[1]);
		$sim_by_dn = explode("*", $sim_enq_num_arr[3]);
		if($sim_enq_num_arr[1]!==""){
			$sim_enq_arr['ic'] = DB::table('enquiry')->select('sr_no', 'enquiry_no', 'enquiry_submitted', 'quotation_no')->whereIn('enquiry_no', $sim_by_ic)->get();
		} 
		if($sim_enq_num_arr[3]!==""){
			$sim_enq_arr['dn'] = DB::table('enquiry')->select('sr_no', 'enquiry_no', 'enquiry_submitted', 'quotation_no')->whereIn('enquiry_no', $sim_by_dn)->get();
		}
		$sim_enq_arr = json_encode($sim_enq_arr);
		// dd($sim_enq_arr);
		return view('similar-enquiries')->with(['sim_enq_data'=>$sim_enq_arr, 'this_enq_num'=>$this_enq_num]);
	}
	
	//for getting client, plant,contact person Id from name.
	public function getID($name, $type){
		switch($type)
		{
			case 'clients':
				$model = 'Clients';
				$column = 'client_id';
				$search_column = 'client_name';
				break;
				
			case 'plants':
				$model = 'Plants';
				$column = 'plant_id';
				$search_column = 'plant_name';
				break;
				
			case 'clients_contact':
				$model = 'ClientsContact';
				$column = 'contact_id';
				$search_column = 'person_name';
				break;
			
			default:
				return "Entry not found";
				break;
				
		}
		$query = DB::table($type)->select($column)->where($search_column, '=', $name)->first();
		//return $query['attributes']['client_id'];
		if(count($query) == 0)
		{
			return "Not found";
		}
		else
		{
			return $query->$column;
		}
		
	}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $cost_sheet_status="no")
    {
        if($id == "submitted")
		{
			$enquiries = DB::table('enquiry')
            ->join('clients', 'enquiry.client_id', '=', 'clients.client_id')
            ->join('plants', 'enquiry.plant_id', '=', 'plants.plant_id')
            ->join('clients_contact', 'enquiry.contact_person', '=', 'clients_contact.contact_id')
            ->join('attachments', 'enquiry.attachment_id', '=', 'attachments.attachment_id')
            ->select('enquiry.*', 'clients.client_name', 'plants.plant_name', 'clients_contact.person_name', 'attachments.*')
			->where('enquiry.quotation_no', '!=', "")
            ->get();
			$enquiries = json_decode(json_encode($enquiries), true);
		  $prod_data = array();
			foreach($enquiries as $data)
			{
				$products_arr = explode(",", $data['product_id']);
				$pro_data = DB::table('products as p')->select('p.*', 'u.unit_name')->leftJoin('units as u', 'p.unit_id', 'u.unit_id')->whereIn('p.product_id', $products_arr)->get();
				$pro_data = json_decode(json_encode($pro_data), true);
				array_push($prod_data, $pro_data);
			}
			
			$cost_sheet_status = "";

			return view('view-enquiries')->with(['enquiry_data'=> $enquiries, 'product_data'=> $prod_data, 'show_type'=> 'submitted', 'cost_sheet_status'=>$cost_sheet_status]);
		}
		else if($id == "pending")
		{
			$enquiries = DB::table('enquiry')
            ->join('clients', 'enquiry.client_id', '=', 'clients.client_id')
            ->join('plants', 'enquiry.plant_id', '=', 'plants.plant_id')
            ->join('clients_contact', 'enquiry.contact_person', '=', 'clients_contact.contact_id')
            ->join('attachments', 'enquiry.attachment_id', '=', 'attachments.attachment_id')
            ->select('enquiry.*', 'clients.client_name', 'plants.plant_name', 'clients_contact.person_name', 'attachments.*')
			->where('enquiry.quotation_no', '=', "");

            if($cost_sheet_status==="with-cost-sheet"){
				$enquiries = $enquiries->where('attachments.cost_sheet_path', '<>', '')->get();
			} else {
				$enquiries = $enquiries->where('attachments.cost_sheet_path', '')->get();
			}
			$enquiries = json_decode(json_encode($enquiries), true);
			$prod_data = array();
			foreach($enquiries as $data)
			{
				$products_arr = explode(",", $data['product_id']);
				$pro_data = DB::table('products as p')->select('p.*', 'u.unit_name')->leftJoin('units as u', 'p.unit_id', 'u.unit_id')->whereIn('p.product_id', $products_arr)->get();
				$pro_data = json_decode(json_encode($pro_data), true);
				array_push($prod_data, $pro_data);
			}
			
			return view('view-enquiries')->with(['enquiry_data'=>$enquiries, 'product_data'=>$prod_data, 'show_type'=>'pending', 'cost_sheet_status'=>$cost_sheet_status]);
		}
		else{
			$enq_cli_att_data = DB::table('enquiry as e')
	            ->join('clients as c', 'e.client_id', 'c.client_id')
	            ->join('plants as p', 'e.plant_id', 'p.plant_id')
	            ->join('clients_contact as cc', 'e.contact_person', 'cc.contact_id')
	            ->join('attachments as a', 'e.attachment_id', 'a.attachment_id')
	            ->select('e.enquiry_no', 'e.sr_no', 'e.enquiry_date', 'e.due_date', 'e.enquiry_submitted', 'e.quotation_no', 'e.quotation_file_name as att_quotation_file_name', 'e.quotation_revisions', 'e.product_id as products', 'e.total_order_value', 'e.lowest_rate', 'c.client_name as cli_client_name', 'cc.person_name as cli_person_name', 'cc.person_mobile as cli_person_mobile', 'cc.person_email as cli_person_email', 'p.plant_name as cli_plant_name', 'p.plant_state as cli_plant_state', 'a.client_drawing_path as att_client_drawing_path', 'a.client_drwaing_no as att_client_drwaing_no', 'a.cost_sheet_path as att_cost_sheet_path', 'a.aew_drawing_path as att_aew_drawing_path', 'a.enquiry_mail_path as att_enquiry_mail_path')->where('e.sr_no', $id)
	            ->first();
	        $c_arr = $p_arr = $a_arr = array();
			$enq_cli_att_arr = json_decode(json_encode($enq_cli_att_data), true);
			foreach($enq_cli_att_arr as $key => $value){
				if(preg_match('/^(cli_|att_)/', $key)){
					$a_n = preg_match('/^(cli_)/', $key) ? "c" : "a";
					${$a_n."_arr"}[substr($key, 4)] = $value;
					unset($enq_cli_att_arr[$key]);
				}
			}

			$products_arr = explode(",", $enq_cli_att_arr['products']);
            $pro_data = DB::table('products as p')->select('p.*', 'u.unit_name')->leftJoin('units as u', 'p.unit_id', 'u.unit_id')->whereIn('p.product_id', $products_arr)->get();
			$p_arr = json_decode(json_encode($pro_data), true);

			$enquiry_data = array(
				'enquiry_details'=>$enq_cli_att_arr,
				'client_details'=>$c_arr,
				'product_details'=>$p_arr,
				'attachment_details'=>$a_arr,
				'ver_sep'=>$this->ver_sep
			);
			return view('view-all-enquiries')->with('enquiry_data', $enquiry_data);
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
		$edit_data = DB::table('enquiry as e')
	            ->join('clients as c', 'e.client_id', 'c.client_id')
	            ->join('plants as p', 'e.plant_id', 'p.plant_id')
	            ->join('clients_contact as cc', 'e.contact_person', 'cc.contact_id')
	            ->join('attachments as a', 'e.attachment_id', 'a.attachment_id')
				->leftjoin('products as pr', 'pr.enquiry_no', '=', 'e.enquiry_no')
	            ->select('e.enquiry_no', 'e.sr_no', 'e.enquiry_date', 'e.due_date', 'e.enquiry_submitted', 'e.quotation_no', 'e.quotation_file_name as att_quotation_file_name', 'e.quotation_revisions', 'e.total_order_value', 'e.lowest_rate', 'c.client_name as cli_client_name', 'cc.person_name as cli_person_name', 'cc.person_mobile as cli_person_mobile', 'cc.person_email as cli_person_email', 'p.plant_name as cli_plant_name', 'p.plant_state as cli_plant_state', 'a.client_drawing_path as att_client_drawing_path', 'a.client_drwaing_no as att_client_drwaing_no', 'a.cost_sheet_path as att_cost_sheet_path', 'a.aew_drawing_path as att_aew_drawing_path', 'a.enquiry_mail_path as att_enquiry_mail_path', DB::raw("(GROUP_CONCAT(pr.product_id SEPARATOR ',')) as 'product_id'"), DB::raw("(GROUP_CONCAT(pr.product_name SEPARATOR ',')) as 'product_name'"), DB::raw("(GROUP_CONCAT(pr.product_item_code SEPARATOR ',')) as 'product_item_code'"), DB::raw("(GROUP_CONCAT(pr.product_quantity SEPARATOR ',')) as 'product_quantity'"), DB::raw("(GROUP_CONCAT(pr.product_rate SEPARATOR ',')) as 'product_rate'"), DB::raw("(GROUP_CONCAT(pr.unit_id SEPARATOR ',')) as 'product_unit_id'"), DB::raw("(GROUP_CONCAT(pr.drawing_no SEPARATOR ',')) as 'product_drawing_no'"))
				->groupBy('pr.enquiry_no')->where('e.sr_no', $id)
	            ->get();
				
		$clients = Clients::all(['client_id', 'client_name']);
        $client_names_arr = $all_states = [];
        foreach($clients as $client){
            $client_names_arr[$client->client_id] = $client->client_name;
        }
        $fetched_states = DB::table('states')->select('state')->where('active', 1)->get();
        foreach($fetched_states as $state){
            $all_states[] = $state->state;
        }
		$unit_data = DB::table('units')->get();
		
		$today_entries = DB::table('enquiry')->count();
        $clients_index_data = ['all_states'=>$all_states, 'all_clients'=>$client_names_arr, 'today_entries'=>$today_entries, 'unit_data'=>$unit_data];
		
	       // $c_arr = $p_arr = $a_arr = array();
			$edit_data = json_decode(json_encode($edit_data), true);

			return view('edit-enquiries')->with('edit_data', $edit_data)->with('clients_index_data', $clients_index_data);
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
        //collection data from request
		$data = $request->all();
		
		//If request is from submitted enquiry
		if($data['update_type'] == "submitted")
		{
			$lowest_rate = "";
			$input_count = $data['input_count'];
			$counter_lowest = 1;
			while($input_count >= $counter_lowest)
			{
				$lowest_rate .= $data['lowest_rate'.$counter_lowest].",";
				
				$counter_lowest++;
			}
			$lowest_rate = rtrim($lowest_rate, ',');
			$query = DB::table('enquiry')
				->where('sr_no', $id)
				->update(['lowest_rate' => $lowest_rate]);
				
			Alert::success('Lowest rate is successfully updated', 'Updated!')->html()->persistent('Ok');
			return redirect('/add-enquiry/'.$id);
		}
		
		//If request is from pending enquiry
		else{
			//dd($data);
			/*$enquiry_no = $data['enquiry_no'];
			
			$counter_costsheet = (int)StringHelper::sanitize($data['counter_for_costsheet']);
			$counter_aewdrawing = (int)StringHelper::sanitize($data['counter_for_aewdrawing']);
			
			$client_drawing_path = "documents/".$enquiry_no."/Client Drawing/";
			$cost_sheet_path = "documents/".$enquiry_no."/Cost Sheet/";
			$aew_drawing_path = "documents/".$enquiry_no."/AEW Drawing/";
			$enquiry_mail_path = "documents/".$enquiry_no."/Enquiry mail copy";
			
			// make missing directories recursively
			if(!file_exists($client_drawing_path)){
				mkdir($client_drawing_path, 0755, true);
			}
			if(!file_exists($cost_sheet_path)){
				mkdir($cost_sheet_path, 0755, true);
			}
			if(!file_exists($aew_drawing_path)){
				mkdir($aew_drawing_path, 0755, true);
			}
			if(!file_exists($aew_drawing_path)){
				mkdir($aew_drawing_path, 0755, true);
			}
			
			$cost_sheet = "";
			$aew_drawing = "";
			$enquiry_mail_copy = "";
			
			if(isset($data['cost_sheet_upload1']))
			{
				while($counter_costsheet != 0)
				{
					$imageName = StringHelper::stringReplacecommabyunderscore($request->file('cost_sheet_upload'.$counter_costsheet)->getClientOriginalName());
					$temp = $request->file('cost_sheet_upload'.$counter_costsheet)->move($cost_sheet_path, $imageName);
					$cost_sheet .=  $imageName.",";
					$counter_costsheet--;
				}
				$cost_sheet = rtrim($cost_sheet, ',');
			}
			
			if(isset($data['aew_drawing_upload1']))
			{
				while($counter_aewdrawing != 0)
				{
					$imageName = StringHelper::stringReplacecommabyunderscore($request->file('aew_drawing_upload'.$counter_aewdrawing)->getClientOriginalName());
					$temp = $request->file('aew_drawing_upload'.$counter_aewdrawing)->move($aew_drawing_path, $imageName);
					$aew_drawing .=  $imageName.",";
					$counter_aewdrawing--;
				}
				$aew_drawing = rtrim($aew_drawing, ',');
			}
			
			if(isset($data['enquiry_mail']))
			{
				$imageName = StringHelper::stringReplacecommabyunderscore($request->file('enquiry_mail')->getClientOriginalName());
				$temp = $request->file('enquiry_mail')->move($enquiry_mail_path, $imageName);
				$enquiry_mail_copy = $imageName;
			}
			
			$attachment_id = DB::table('enquiry')->select('attachment_id')->where('enquiry_no', $enquiry_no)->get();
			$attachment_id = json_decode(json_encode($attachment_id), true);
			$attachment_id = $attachment_id['0']['attachment_id'];

			$query = DB::table('attachments')
				->where('attachment_id', $attachment_id)
				->update(['cost_sheet_path' => $cost_sheet, 'aew_drawing_path' => $aew_drawing, 'enquiry_mail_path' => $enquiry_mail_copy]);
				
			Alert::success('Drawings are uploaded Successfully ', 'Uploaded!')->html()->persistent('Ok');
			return redirect('/add-enquiry/'.$id);	*/
			
			
			//getting respective attachment data based on sr_no of enquiry which is primary key of table enquiry
			
			
			$attachment_query = DB::table('enquiry')
				->join('attachments', 'enquiry.attachment_id', '=', 'attachments.attachment_id')
				->select('attachments.*', 'enquiry.enquiry_no', 'enquiry.product_id')
				->where('enquiry.sr_no', '=', $id)
				->get();
			
			$attachment_data = json_decode(json_encode($attachment_query), true);
			//dd($attachment_data);
			$enquiry_product_id = $attachment_data[0]['product_id'];
			$enquiry_no = $attachment_data[0]['enquiry_no'];
			$attachment_id = $attachment_data[0]['attachment_id'];
			$client_drawing_path = explode(',', $attachment_data[0]['client_drawing_path']); // array of , seperated drawing path

			$client_drwaing_no = explode(',', $attachment_data[0]['client_drwaing_no']); // array of , seperated drawing no
			
			$client_drwaing_no_db = $client_drwaing_no;
			$cost_sheet_path_db = explode(',', $attachment_data[0]['cost_sheet_path']);
			$aew_drawing_path_db = explode(',', $attachment_data[0]['aew_drawing_path']);
			$enquiry_mail_path_db = $attachment_data[0]['enquiry_mail_path'];
			$total_drawings = $data['counter_for_drawing_no'];
			
			if($data['deleted_client_drawing'] != "")
			{	
				$deleted_client_drawings = explode(',', rtrim($data['deleted_client_drawing'], ',')); // array of deleted drawings
			}
			else{
				$deleted_client_drawings = array();
			}


			
			
			//Removing deleted drawings from the array of database values
			foreach($deleted_client_drawings as $data_cld)
			{
				$data_cld = (int)$data_cld;

				unset($client_drwaing_no[$data_cld]);
				$client_drwaing_no = array_values($client_drwaing_no);
				unset($client_drawing_path[$data_cld]);
				$client_drawing_path = array_values($client_drawing_path);
			}
			
			
			$client_drawing_path_fol = "documents/".$enquiry_no."/Client Drawing/";
			$cost_sheet_path = "documents/".$enquiry_no."/Cost Sheet/";
			$aew_drawing_path = "documents/".$enquiry_no."/AEW Drawing/";
			$enquiry_mail_path = "documents/".$enquiry_no;
			// make missing directories recursively
			if(!file_exists($client_drawing_path_fol)){
				mkdir($client_drawing_path_fol, 0755, true);
			}
			if(!file_exists($cost_sheet_path)){
				mkdir($cost_sheet_path, 0755, true);
			}
			if(!file_exists($aew_drawing_path)){
				mkdir($aew_drawing_path, 0755, true);
			}
			
			//Getting edited data
			$edited_client_drawings = explode(',', rtrim($data['edited_client_drawing'], ','));
			foreach($edited_client_drawings as $data_edt)
			{
				$data_edt = (int)$data_edt;
				
				 //To check if edited drawing is already deleted
				if(!in_array($data_edt, $deleted_client_drawings))
				{
					$client_drwaing_no[$data_edt] = $data["client_drawing_no_".$data_edt];
				
					if(isset($data["client_drawing_path_".$data_edt]))
					{
						$imageName = StringHelper::stringReplacecommabyunderscore($request->file('client_drawing_path_'.$data_edt)->getClientOriginalName());
						
						$temp = $request->file('client_drawing_path_'.$data_edt)->move($client_drawing_path_fol, $imageName);
						
						$client_drawing_path[$data_edt] = $imageName;
					}
				}
				//$client_drawing_path[$data_edt];
			}
			
			
			//Getting new added data
			for($i = sizeof($client_drwaing_no_db); $i < (int)($data['counter_for_drawing_no']); $i++)
			{
				if(isset($data['client_drawing_no_'.$i]))
				{
					array_push($client_drwaing_no, $data['client_drawing_no_'.$i]);
				}
				
				if(isset($data["client_drawing_path_".$i]))
				{
					$imageName = StringHelper::stringReplacecommabyunderscore($request->file('client_drawing_path_'.$i)->getClientOriginalName());
					
					$temp = $request->file('client_drawing_path_'.$i)->move($client_drawing_path_fol, $imageName);
					
					$client_drawing_path[$i] = $imageName;
				}
				
			}
			
			$client_drwaing_no = implode(',',$client_drwaing_no); // Final client drawing no
			
			$client_drawing_path = implode(',',$client_drawing_path); // Final client drawing path

			
			//Products
			
			$counter_for_products = (int)StringHelper::sanitize($data['counter_for_products']);
			if($data['deleted_products'] != "")
			{
				$deleted_products = explode(',', rtrim($data['deleted_products'], ',')); // array of deleted products
			}
			else{
				$deleted_products = array();
			}
			$product_item_code = "";
			$product_name = "";
			$product_quantity = "";
			$product_unit = NULL;
			$product_data_update = array();
			$product_data_insert = array();
			$product_id_update = array();
			$product_id_insert = array();
			$temp_arr = $temp_prod = $temp_draw = $temp_itco = array();
			while($counter_for_products != -1)
			{
				if((!in_array((string)$counter_for_products, $deleted_products, TRUE)) && (isset($data['item_code'.$counter_for_products])))
				{	
					$temp_itco[] = $item_code = StringHelper::sanitize($data['item_code'.$counter_for_products]);
					$temp_prod[] = $product_name = StringHelper::sanitize($data['product_name'.$counter_for_products]);
					$product_quantity = StringHelper::sanitize($data['product_quantity'.$counter_for_products]);
					$product_unit = StringHelper::sanitize($data['product_unit'.$counter_for_products]);
					if(gettype($data['product_drawing'.$counter_for_products])==="array"){
						$temp_draw[] = $product_drawing_no = StringHelper::sanitize(implode(",", $data['product_drawing'.$counter_for_products]), ",");
					} else {
						$temp_draw[] = $product_drawing_no = $data['product_drawing'.$counter_for_products];
					}
					

					if(isset($data['product_id'.$counter_for_products]))
					{	

						array_push($product_id_update, (string)$data['product_id'.$counter_for_products]);
						
						$temp_product_data = array("product_name"=>$product_name, "product_item_code"=>$item_code, "product_quantity"=>$product_quantity, "unit_id"=>$product_unit, "drawing_no"=>$product_drawing_no);
						
						array_push($product_data_update, $temp_product_data);
					}
					else{

						$temp_product_data = array("enquiry_no"=>$enquiry_no, "product_name"=>$product_name, "product_item_code"=>$item_code, "product_quantity"=>$product_quantity, "unit_id"=>$product_unit, "drawing_no"=>$product_drawing_no);
						
						array_push($product_data_insert, $temp_product_data);
					}
					
					
				}
				$counter_for_products--;
			}
			
			//inserting new added products
			$updated_id = "";
			foreach($product_data_insert as $prod_data)
			{
				$insert_products = DB::table('products')->insertGetId($prod_data);
				if($insert_products != "")
				{
					$updated_id = $updated_id.$insert_products.",";
				}
			}
			$updated_id = rtrim($updated_id, ',');
			$updated_id = ltrim($updated_id, ',');
			
			$enquiry_product_id = $enquiry_product_id.",".$updated_id;
			$enquiry_product_id = rtrim($enquiry_product_id, ',');
			$enquiry_product_id = ltrim($enquiry_product_id, ',');
			$enquiry_date_up = StringHelper::sanitize($data['enquiry_date'], ":");
			$due_date_up = StringHelper::sanitize($data['due_date'], ":");
			
			//updating inserted product id in enquiry table
			$update_attachment = DB::table('enquiry')
				->where('enquiry.sr_no', $id)
				->update(['product_id' => $enquiry_product_id, "enquiry_date" => $enquiry_date_up, "due_date" => $due_date_up]);
			
			//updating editedproducts
			for($i = 0; $i < sizeof($product_id_update); $i++)
			{
				$prod_id = (int)$product_id_update[$i];
				$query = DB::table('products')
				->where('product_id', $prod_id)
				->update($product_data_update[$i]);
			}
			
			//updating other enquiry details
			//$client_name_up = StringHelper::sanitize($data['client_name'], ".");
			//$plant_name_up = StringHelper::sanitize($data['plant_name'], ".");
			
			
			//$contact_person_up = StringHelper::sanitize($data['contact_person'], ".");
			
			/*$temp_arr['item_codes'] = $temp_itco;
			$temp_arr['product_names'] = $temp_prod;
			$temp_arr['drawing_numbers'] = $temp_draw;*/
			
			
			
			//Cost Sheet
			$counter_costsheet = (int)StringHelper::sanitize($data['counter_for_costsheet']);
			//dd($counter_costsheet);
			if($data['deleted_costsheet'] != "")
			{
				$deleted_costsheet = explode(',', rtrim($data['deleted_costsheet']));
			}
			else{
				$deleted_costsheet = array();
			}
			
			/*for($i =0; $i < sizeof($cost_sheet_path_db))
			{
				
			}
			*/
			$cost_sheet = "";
			
			foreach($deleted_costsheet as $cost_deleted){
				unset($cost_sheet_path_db[$cost_deleted]);
			}
			
			$cost_sheet = implode(",", $cost_sheet_path_db).",";
			/*if((sizeof($cost_sheet_path_db) != $counter_costsheet) && (!is_null($deleted_costsheet)))
			{*/
				//$counter_costsheet = $counter_costsheet-1;
				while($counter_costsheet != -1)
				{
					
					if(isset($data["cost_sheet_upload".$counter_costsheet]))
					{
						$imageName = StringHelper::stringReplacecommabyunderscore($request->file('cost_sheet_upload'.$counter_costsheet)->getClientOriginalName());
						$temp = $request->file('cost_sheet_upload'.$counter_costsheet)->move($cost_sheet_path, $imageName);
						$cost_sheet .=  $imageName.",";
					}
					$counter_costsheet--;
				}
			/*}
			else
			{
				$cost_sheet = implode(",", $cost_sheet_path_db);
			}*/
			$cost_sheet = rtrim($cost_sheet, ',');
			$cost_sheet = ltrim($cost_sheet, ',');
			
			//dd($cost_sheet);
			
			
			
			//AEW Drawing
			$counter_aewdrawing = (int)StringHelper::sanitize($data['counter_for_aewdrawing']);

			if($data['deleted_aewdrawing'] != "")
			{
				$deleted_aewdrawing = explode(',', rtrim($data['deleted_aewdrawing']));
			}
			else{
				$deleted_aewdrawing = array();
			}
			
			
			$aew_drawing = "";
			
			foreach($deleted_aewdrawing as $aew_deleted){
				unset($aew_drawing_path_db[$aew_deleted]);
			}
			
			$aew_drawing = implode(",", $aew_drawing_path_db).",";
				while($counter_aewdrawing != -1)
				{
					
					if(isset($data["aew_drawing_upload".$counter_aewdrawing]))
					{
						$imageName = StringHelper::stringReplacecommabyunderscore($request->file('aew_drawing_upload'.$counter_aewdrawing)->getClientOriginalName());
						$temp = $request->file('aew_drawing_upload'.$counter_aewdrawing)->move($aew_drawing_path, $imageName);
						$aew_drawing .=  $imageName.",";
					}
					$counter_aewdrawing--;
				}
			/*}
			else
			{
				$cost_sheet = implode(",", $cost_sheet_path_db);
			}*/
			$aew_drawing = rtrim($aew_drawing, ',');
			$aew_drawing = ltrim($aew_drawing, ',');

			//dd($aew_drawing);
			
			
			//Enquiry Mail copy
			
			$enquiry_mail_copy = "";
			$deleted_enquiry_copy = $data['deleted_enquiry_copy'];
			if($deleted_enquiry_copy == "1")
			{
				if(isset($data['enquiry_mail']))
				{
					$imageName = StringHelper::stringReplacecommabyunderscore($request->file('enquiry_mail')->getClientOriginalName());
					$temp = $request->file('enquiry_mail')->move($enquiry_mail_path, $imageName);
					$enquiry_mail_copy = $imageName;
				}
				else
				{
					$enquiry_mail_copy = "";
				}		
			}
			else
			{
				$enquiry_mail_copy = $enquiry_mail_path_db;
			}
			//dd($enquiry_mail_copy);
			
			//dd($client_drawing_path."+ asd +".$client_drwaing_no);
			
			$update_attachment = DB::table('attachments')
				->where('attachment_id', $attachment_id)
				->update(['client_drawing_path' => $client_drawing_path, 'client_drwaing_no' => $client_drwaing_no, 'cost_sheet_path' => $cost_sheet, 'aew_drawing_path' => $aew_drawing, 'enquiry_mail_path' => $enquiry_mail_copy]);
				
				
				
			Alert::success('Enquiry Updated successfully, you need to re-generate quotation if it was a submitted enquiry', 'Updated!')->html()->persistent('Ok');
			return redirect('/add-enquiry/'.$id);
		}
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

    // temp func - will be moved later(this function belongs to string helper class)
    // deprecated since comma will be used as a separator
    public static function splitProducts($product_string){
    	$result = array();
    	$product_array = explode("__", $product_string);
    	$result['length'] = $result['count'] = count($product_string);
    	$result['data'] = $product_string;
    	return $result;
    }

    /**
     * Find the next quoation numbber that will be allotted.
     * If the quotation is generated successfullt, this number needs to be saved
     * in the database in `enquiry` table
     *
     * @param  none
     * @return String - Quotation Number
     */
    public static function nextQuotationNumber(){
    	$total_closed_enquiries = DB::table('enquiry')->where('quotation_no', '<>', '')->whereYear('enquiry_date', date('Y'))->count();
    	return date('Y')."-AEW-".sprintf("%03d", ++$total_closed_enquiries);
    }
	
	
	/**
     * Show view for uploading Enquiries CSV.
     *
     * @param  none
     * @return view
     */
    public function processEnquiryCSV(Request $request){
    	echo "processing csv<hr>";
    	return $request->all();
    }
	
    /**
     * Process Enquiries CSV to save all enquiry data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Redirection
     */
    public function generateQuotationView($enq_num=""){
    	
    	// $open_enquiries_arr = DB::table('enquiry')->where('quotation_no', '')->pluck('enquiry_no');
    	$open_enquiries_arr = DB::table('enquiry')->pluck('enquiry_no');
    	return view('generate-quotation', ['open_enquiries'=>$open_enquiries_arr, 'enq_num'=>$enq_num]);
    }

    /**
     * Check if quotation for the given enquiry already exists
     *
     * @param  posted Request
     * @return string containing status
     */
    public function checkQuotationExistence(Request $request){
		$result = array();
    	$enq_num = $request->all()['e_n'];
    	$enq_check = DB::table('enquiry')->select('quotation_no', 'product_id')->where('enquiry_no', $enq_num);
    	$enq_exists = $enq_check->count();
    	if($enq_exists){
    		$fetched_quotation = $enq_check->pluck('quotation_no')[0];
			$result['response'] = $fetched_quotation!=="" ? "quotationexists" : "quotationdoesnotexist";
			$result['existing_qtn'] = $fetched_quotation;
			$result['product_details'] = array();
			$products_id = $enq_check->pluck('product_id')[0];
			$products_arr = explode(",", $products_id);
			// DB::connection()->enableQueryLog();
			$result['product_details'] = DB::table('products as p')->select('p.*', 'u.unit_name')->leftJoin('units as u', 'p.unit_id', 'u.unit_id')->whereIn('p.product_id', $products_arr)->get();
			// $result['query'] = DB::getQueryLog();
    	} else {
    		$result['response'] = "enquirydoesnotexist";
    	}
    	return json_encode($result);
    }

    /**
     * Generate Quotation PDF file from posted quotation & its enquiry data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Output PDF File & Redirection
     */
    public function generateQuotation(Request $request){
    	
    	$posted_data = $request->all();
    	// return $posted_data;
    	$enq_num = trim($posted_data['enquiry_number']);
    	$enq_check = DB::table('enquiry')->select('quotation_no', 'quotation_file_name', 'quotation_revisions')->where('enquiry_no', $enq_num);
			$enq_exists = $enq_check->count();
			if($enq_exists){
				$fetched_quotation_filename = trim($enq_check->pluck('quotation_file_name')[0]);
				$fetched_quotation_no = trim($enq_check->pluck('quotation_no')[0]);
				$quo_rev = trim($enq_check->pluck('quotation_revisions')[0]);
				$products_note = isset($posted_data['products_note']) ? $posted_data['products_note'] : "";
				$excise_duty = $posted_data['excise_duty'];
		    	$excise_duty = preg_replace(['/%/', '/ %/'], "", $excise_duty);
		    	$delivery_details = $posted_data['delivery_details'];
		    	$payment_details = $posted_data['payment_details'];
		    	$packing_details = $posted_data['packing_details'];
		    	$enquiry = DB::table('enquiry')->join('plants', 'enquiry.plant_id', 'plants.plant_id')->join('clients', 'clients.client_id', 'plants.client_id')->where('enquiry_no', $enq_num)->first();
		    	$plant_name = $enquiry->plant_name;
				$quo_rev_new = 0;
		    	if($fetched_quotation_filename!==""){
		    		$ver_sep = $this->ver_sep;// versioning separator
		    		$quo_rev_new = $quo_rev+1;
		    		if($quo_rev==0){
		    			$quotation_num = $fetched_quotation_no.$ver_sep.$quo_rev_new;
		    		} else {
		    			if($ver_sep=="-"){
		    				$quotation_num = substr($fetched_quotation_no, 0, 12)."-".$quo_rev_new;
		    			} else {
		    				$quotation_num = explode("/", $fetched_quotation_no)[0]."/".$quo_rev_new;	    				
		    			}
		    		}
		    		$file_name = "Quotation ".$quotation_num." - ".$plant_name.".pdf";;
		    	} else {
		    		$quotation_num = $this->nextQuotationNumber();
		    		$file_name = "Quotation ".$quotation_num." - ".$plant_name.".pdf";
		    	}
		    	$products_arr = explode(",", $enquiry->product_id);
		    	$products = DB::table('products as p')->select('p.*', 'u.unit_name')->leftJoin('units as u', 'p.unit_id', 'u.unit_id')->whereIn('p.product_id', $products_arr)->get();
		    	// echo "<pre>".json_encode($enquiry)."</pre>";
		    	$aew_logo = 'images/aew_logo.png';
		    	$aew_sign = 'images/signature.png';
		    	$quotation_html = "";
		    	$prod_desc_wid = "200px";
		    	$quotation_head = "<html><head><style>
				    body, a, table, td>i{color: #111; font-family: Helvetica;}
				    a{text-decoration: none;}
				    .red{color: rgb(204, 0, 0);}
				    .blue{color: rgb(69, 43, 187);}
				    .width80{width: 79%;}
				    .width60{width: 59%;}
				    .width40{width: 40%;}
				    .width30{width: 30%;}
				    .width20{width: 20%;}
				    .width10{width: 10%;}
				    .text-left{text-align: left;}
				    .text-center{text-align: center;}
				    .text-right{text-align: right;}
				    .quotation_master_wrapper{ border: 2px solid; }
				    table td, tr.thead th{ border-bottom: 2px solid; padding: 2px 5px; }
				    .product_row>td { height: 40px;}
				    table tbody>.tbody td{ border-bottom: none; }
				    table .tfoot td{ border-top: 2px solid; }
				    table tr.no_border td{border: none;}
				    table.borderless_table td, td.has_table_child>table{border: none;}
				    td.has_table_child { vertical-align: top; }
				    td.has_table_child>table { font-size: 10px; }
				    td.has_table_child, .border_left{border-left: 2px solid;width: 50%;}
				    h4, h5, h6, h1, h2, h3{font-weight: bold;}
				    tr.tr_company_info, tr.tr_company_address, tr.tr_quotation_thankyou{text-align: center;}
				    tr.tr_company_address p, tr.tr_quotation_thankyou p{font-size: 10px; font-weight: bold; margin: 0;}
				    tr.tr_company_address p.gst_num{ font-size: 12px; text-align: left; }
				    tr.tr_quotation_details{font-size: 12px;}
				    tr.tr_company_info>.aew_logo_wrapper{position: relative; height: 90px;}
				    tr.tr_company_info>.aew_logo_wrapper>h3{font-size: 3em; font-family: serif; height: 1em; margin: 10px;}
						tr.tr_company_info>.aew_logo_wrapper>h6, tr.tr_company_info>.aew_logo_wrapper>h5{ font-size: 10px; height: 0px; margin: 0px; display: inline-block; width: 100%; }
						tr.enq_num_tr>td{ padding-top: 15px; }
				    tr.tr_company_info>.aew_logo_wrapper>img{position: absolute; left: 20px; top: 20px; width: 50px;}
				    table{width: 100%; font-size: 12px; border-spacing: 0;}
				    .quotation_for{text-transform: uppercase;}
				    .quotation_for p{margin: 0;}
				    div.quotation_footer{position: relative; height: 180px;}
				    div.auth_sign_wrapper{position: absolute; right: 0; top: 0; padding: 16px; text-align: right; font-size: 12px;}
				    tr.thead th{text-align: center;}
				    tr.tfoot td, table.width60{font-weight: bold;}
				      table.width60 td, table.width60{width: 60%; border: none;}
				    img.aew_sign{height: 65px;}
				    .lpadding{padding-left: 25px;}
				    .table_border_helper { display: inline-block; width: 100%; }
				    .cf_foot>td { border-bottom: none; border-top: 2px solid; }
				    .cf_foot>td, .bf_foot>td{ font-weight: bold; }
				</style></head><body>";
					function getQuotationHeader($has_pb_before=false){
		      	return "<div class='quotation_master_wrapper' style='".($has_pb_before ? "
		      		page-break-before: always;" : "")."'>";
					}
			   	$quotation_header = "<div class='table_border_helper'>
				      <table class='quotation_header'>
				        <tr class='tr_company_info'>
				          <td colspan='2' class='aew_logo_wrapper'>
				            <img src='$aew_logo'/>
				            <h5>Quotation</h5>
				            <h3 class='red'>Ashok Engineering Works</h3>
				            <h6 class='blue'><i>Manufacturer of Cement Plant Machinery Spares & Specialist in Elevator Buckets, Roller Assembly & Spares of Roller Press</i></h6>
				          </td>
				        </tr>
				        <tr class='tr_company_address'>
				          <td colspan='2'>
				            <p>Admn. Office : 111, Udyog Bhavan, Sonawala Road, Goregaon (East), Mumbai - 400063 (India)</p>
				            <p>Tel. : 91-22-26862565 / 26861541 / 26856223 E-mail : <a href='mailto:aew111mumbai@gmail.com' target='_blank'>aew111mumbai@gmail.com</a></p>
				            <p>Works : Lane DD-1 Khan Real Industrial Estate, Near Vasai Phata, Western Express Highway, Vasai (E), Thane -401208 (Maharashtra)</p>
				            <p>Website : <a href='www.ashokengineeringworks.in' target='_blank'>www.ashokengineeringworks.in</a></p>
				            <p class='gst_num'><b><u>GST NO: 27AEMPR4533D1ZE</u></b></p>
				          </td>
				        </tr>
				        <tr class='tr_quotation_details'>
				          <td class='quotation_for'>
				            <p>M/s. ".$enquiry->client_name." </p>
				            <p class='lpadding'>(UNIT : ".$enquiry->plant_name.")</p>
				            <p class='lpadding'>".$enquiry->plant_address."</p>
				            <p class='lpadding'>".$enquiry->plant_city."</p>
				            <p class='lpadding'>".$enquiry->plant_state."</p>
				          </td>
				          <td class='has_table_child'>
				            <table class='borderless_table'>
				              <tr>
				                <td class='width30'>Quotation No.: </td>
				                <td class='text-left width30'>".$quotation_num."</td>
				                <td class='text-right width10'>Dt.: </td>
				                <td class='text-left width30'>".date("d-m-Y")."</td>
				              </tr>
				              <tr class='enq_num_tr'>
				                <td class='width30'>Enquiry No. </td>
				                <td class='text-left width30'>".$enquiry->enquiry_no."</td>
				                <td class='text-right width10'>Dt.: </td>
				                <td class='text-left width30'>".date("d-m-Y", strtotime($enquiry->enquiry_date))."</td>
				              </tr>
				            </table>
				          </td>
				        </tr>
				        <tr class='tr_quotation_thankyou'>
				          <td colspan='2'>
				            <p>We thankfully acknowledge your above mentioned enquiry, We hearby offer our most reasonable quotation as under and trust that you will find the same upto your expectation</p>
				          </td>
				        </tr>
				      </table>";
	   		    $prod_table_start = "<table class='quotation_table'>";
			      $prod_table_end = "</table>";
			      $prod_table_head = "<tr class='thead'>
		          <th style='width:15px'>Sr. No.</th>
		          <th style='width:$prod_desc_wid' class='border_left'>Description</th>
		          <th style='width:30px' class='border_left'>Qty.</th>
		          <th style='width:70px' class='border_left'>Rate</th>
		          <th style='width:30px' class='border_left'>Unit</th>
		          <th style='width:70px' class='border_left'>Total Amount</th>
		        </tr>";
		        $quotation_html .= $quotation_head.(getQuotationHeader()).$quotation_header.$prod_table_start.$prod_table_head;
		        $p_c = $p_p_c = 1; $total_o_v = $posted_data['total_order_value'];
		        $prod_page_contd = false;
		        $total = 0;
		        $per_page_prod = 14;
		        $prod_table_notes = $skipped_product_row = $skipped_total_amt = "";
		        $p_total = count($products_arr);
		        $tot_addn_pages = round($p_total/$per_page_prod);
		        $p_p_p_w_f = $tot_addn_pages>1 ? 10 : 11;
		        foreach($products_arr as $product_id){
		        	$show_p_r = $save_skipped = false;
		        	$end_tbl_n_div_html = "";
		        	if($p_p_c <= $per_page_prod){
		        		if($prod_page_contd){
		        			$quotation_html .= (getQuotationHeader(true)).$quotation_header.$prod_table_start.$prod_table_head;
		        			$quotation_html .= "<tr class='bf_foot'>
			              <td colspan='5' class='text-right'>B/F:$tot_addn_pages</td>
			              <td class='text-right border_left'>".IntHelper::intoCurrencyFormat($skipped_total_amt, 2)."</td>
			            </tr>".$skipped_product_row;
			            $skipped_product_row = $skipped_total_amt = "";
		        			if($tot_addn_pages>1){ $p_p_c++; }
		        		}
		        		$show_p_r = true; $prod_page_contd = false;
		          } else {
		          	$p_p_c = 0; $prod_page_contd = true; $save_skipped = true;
		          	$skipped_total_amt = $total;
		          	$quotation_html .= "<tr class='cf_foot'>
		              <td colspan='5' class='text-right'>C/F</td>
		              <td class='text-right border_left'>".IntHelper::intoCurrencyFormat($total, 2)."</td>
		            </tr></table></div></div>";
		          }
		        		$p_r = strtoupper(trim($posted_data["product_".$product_id."_rate"]));
			        	$p_r == "" ? $p_r = "REGRET" : null;
			        	$p_r==="REGRET" ? list($p_r_i, $p_r_d)=array(0, "REGRET") : list($p_r_i, $p_r_d)=array(intval($p_r), IntHelper::intoCurrencyFormat($p_r, 2));
			        	$p_q = intval($posted_data["product_".$product_id."_quantity"]);
			        	$p_a = $p_r_i*$p_q;
			        	$total += $p_a;
			        	$p_a = $p_a===0 ? "" : IntHelper::intoCurrencyFormat($p_a, 2);
			        	$p_u = $posted_data["product_".$product_id."_unitname"];
			        	$p_u_d = $p_u==="N/A"||$p_r==="REGRET" ? "" : ($p_q===1 ? $p_u : $p_u."S");
			        	$p_n = $posted_data["product_".$product_id."_name"]."<br>DR: ".$posted_data["product_".$product_id."_drawing"];
			        	$product_row_html = "<tr class='tbody product_row'>
	                <td style='width:15px; vertical-align:top; text-align: center'><span style='margin:6px 3px 6px auto; display: block;'>$p_c</span></td>
	                <td style='width:$prod_desc_wid;' class='border_left'>$p_n</td>
	                <td style='width:30px;' class='border_left text-center'>".IntHelper::intoCurrencyFormat($p_q)."</td>
	                <td style='width:70px;' class='border_left text-right'>$p_r_d</td>
	                <td style='width:30px;' class='border_left text-center'>$p_u_d</td>
	                <td style='width:70px;' class='border_left text-right'>$p_a</td>
		            </tr>".$end_tbl_n_div_html;
		            DB::table('products')->where('product_id', $product_id)->update(['product_rate'=>$p_r]);
		          if($show_p_r){ $quotation_html .= $product_row_html; }
		          if($save_skipped){ $skipped_product_row = $product_row_html; }
		          $p_p_c++;
	            $p_c++;
		        }
		        if($products_note!==""){
			        $prod_table_notes.= "<tr class='tbody' style='500px'>
                <td style='width:15px;' class='text-center'></td>
                <td style='width:$prod_desc_wid;' class='border_left'><span style='color:red'><u>Note:</u> ".$products_note."</span></td>
                <td style='width:30px;' class='border_left text-center'></td>
                <td style='width:70px;' class='border_left text-right'></td>
                <td style='width:30px;' class='border_left text-center'></td>
                <td style='width:70px;' class='border_left text-right'></td>
	            </tr>";
		        }
		        $extra_blank_rows = $p_p_p_w_f - $p_p_c - 1;
		        for($e_r=1; $e_r<=$extra_blank_rows; $e_r++){
		        	if($e_r===2 || $extra_blank_rows===1){ $quotation_html .= $prod_table_notes; }
		        	$quotation_html .= "<tr class='tbody product_row'>
	                <td style='width:15px; vertical-align:top;' class='text-center'></td>
	                <td style='width:$prod_desc_wid;' class='border_left'></td>
	                <td style='width:30px;' class='border_left text-center'></td>
	                <td style='width:70px;' class='border_left text-right'></td>
	                <td style='width:30px;' class='border_left text-center'></td>
	                <td style='width:70px;' class='border_left text-right'></td>
		            </tr>";
		        }
		        $prod_table_foot = "<tr class='tfoot'>
	              <td colspan='5'>Rupees: ".IntHelper::intoWords($total)." Only</td>
	              <td class='text-right border_left'>".IntHelper::intoCurrencyFormat($total, 2)."</td>
	            </tr>";
	          // $quotation_html .= $prod_table_notes.$prod_table_foot.$prod_table_end;
	          $quotation_html .= $prod_table_foot.$prod_table_end;
	          $quotation_html .= "<div class='quotation_footer'>
			        <table class='width60'>
			          <tr><td colspan='2'><i>Terms and Conditions:-</i></td></tr>
			          <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
			          <tr>
			            <td>Excise Duty</td>
			            <td>: $excise_duty%</td>
			          </tr>";
			      if(isset($posted_data['igst_perc'])){
			      	$quotation_html .= "<tr>
				            <td>IGST</td>
				            <td>: {$posted_data['igst_perc']}%</td>
				          </tr>";
			      } else if(isset($posted_data['cgst_perc'], $posted_data['sgst_perc'])){
			      	$quotation_html .= "<tr>
				            <td>CGST</td>
				            <td>: {$posted_data['cgst_perc']}%</td>
				          </tr>
				          <tr>
				            <td>SGST</td>
				            <td>: {$posted_data['sgst_perc']}%</td>
				          </tr>";
			      } else {
				      $quotation_html .= "<tr>
				            <td>Taxes</td>
				            <td>: -</td>
				          </tr>";
			      }
			      $quotation_html .= "<tr>
			            <td>Delivery</td>
			            <td>: $delivery_details</td>
			          </tr>
			          <tr>
			            <td>Payment</td>
			            <td>: $payment_details</td>
			          </tr>
			          <tr>
			            <td>Packing</td>
			            <td>: $packing_details</td>
			          </tr>
			        </table>
			        <div class='auth_sign_wrapper'>
			          <p>For Ashok Engineering Works</p>
			          <img src='$aew_sign' class='aew_sign'>
			          <p>Authorised Signatory</p>
			        </div>
		        </div>
		      </div>
		    </div>";
	    	$quotation_html.= "</body></html>";
	    	// $fh = fopen("quotation_html.html", "a"); fwrite($fh, $quotation_html); fclose($fh);
	    	// return $quotation_html;
    	// generating quotation pdf
	    	$quotation_path = 'documents/'.$enq_num.'/';
	    	if(!file_exists($quotation_path)){
	    		mkdir($quotation_path, 0755, true);
	    	}
	    	$quotation_path .= $file_name;
				$dompdf = new Dompdf();
				$dompdf->loadHtml($quotation_html);
				$dompdf->setPaper('A4');
				$dompdf->render();
				$pdf_gen = $dompdf->output();
				$this_enq = Enquiry::where('enquiry_no', $enq_num)->first();
				$this_enq->enquiry_submitted = 1;
				$this_enq->quotation_no = $quotation_num;
				$this_enq->quotation_file_name = $file_name;
				$this_enq->quotation_revisions = $quo_rev_new;
				$this_enq->products_note = $products_note;
				if(file_put_contents($quotation_path, $pdf_gen) && $this_enq->save()){
					Alert::success("Quotation for Enquiry Number <b>'".$enq_num."'</b> generated successfully.<br><b>Quotation Number is '".$quotation_num."'</b> <br><a href='$quotation_path' class='btn btn-submit' target='_blank' onclick='javascript: swal.close();'>Download Now</a>", "Quotation Generated!")->html()->persistent('Ok');
				} else {
					Alert::error('Quotation for <b>Enquiry Number '.$enq_num.'</b> cannot be generated at this moment due to some error. Please try again later.', 'Sorry!')->html()->persistent('Ok');
				}
			} else {
				Alert::error("Entered <b>Enquiry Number '".$enq_num."'</b> does not exist. Kindly <a href='/add-enquiry'>add the Enquiry</a> first to generate its Quotation", "Error!")->html()->persistent('Ok');
			}
			// return $quotation_html;
			return redirect('/generate-quotation');
    }

}