<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\StringHelper;
use App\Enquiry;
use App\Order;
use App\Jobs;
use App\Attachments;
use Alert;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    public function pendingOrders()
    {
    	# code...
    	//Added by nitish for order job listing

        $query_fetch = DB::table('jobs as jo')->join('orders as or', 'or.order_id', '=','jo.order_id')->join('plants as pt', 'pt.plant_id', '=', 'or.plant_id')->join('clients as cl', 'cl.client_id', '=', 'pt.client_id')->select('jo.*', 'or.order_num', 'pt.plant_name', 'pt.plant_state', 'pt.plant_city', 'cl.client_name')->where('jo.invoice_status', '=', '0')->get();

       /* foreach ($variable as $key => $value) {
        	# code...
        }*/

    	//dd($query_fetch[3]);
        return view('pending-joblist')->with(['data'=>$query_fetch]);
    }

    public function completedOrders()
    {
    	# code...
    	//Added by nitish for order job listing

        $query_fetch = DB::table('jobs as jo')->join('orders as or', 'or.order_id', '=','jo.order_id')->join('plants as pt', 'pt.plant_id', '=', 'or.plant_id')->join('clients as cl', 'cl.client_id', '=', 'pt.client_id')->select('jo.*', 'or.order_num', 'pt.plant_name', 'pt.plant_state', 'pt.plant_city', 'cl.client_name')->where('jo.invoice_status', '=', '1')->get();

       /* foreach ($variable as $key => $value) {
        	# code...
        }*/

    	//dd($query_fetch[3]);
        return view('completed-joblist')->with(['data'=>$query_fetch]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
         $data = array();
        $data['quotation_nums'] = DB::table('enquiry')->select('quotation_no')->where('enquiry_submitted', '1')/*->where('order_id', '0')*/->get();
        return view('add-order', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $posted_data = $request->all();
        // dd($posted_data);
    // required variables
        $t_y = date('Y'); $t_m = date('n');
        if($t_m<4){ $t_y--; }
        $cfy_start = date("Y-m-d", strtotime($t_y."-04-01"));
        $job_count = DB::table('orders as o')->rightJoin('jobs as j', 'o.order_id', 'j.order_id')->where('o.order_date', '>', $cfy_start)->count();
        $order_path = 'documents/orders/';
        $jobs_path = $order_path.'jobs/';
        $order_copy_path = $order_path.'order_copies/';
        $new_drawing_path = $jobs_path.'new_drawings/';
        $other_attachments_path = $jobs_path.'other_attachments/';
        if(!file_exists($order_path)){ mkdir($order_path, 0755, true); }
        if(!file_exists($jobs_path)){ mkdir($jobs_path, 0755, true); }
        if(!file_exists($order_copy_path)){ mkdir($order_copy_path, 0755, true); }
        if(!file_exists($new_drawing_path)){ mkdir($new_drawing_path, 0755, true); }
        if(!file_exists($other_attachments_path)){ mkdir($other_attachments_path, 0755, true); }
        $order_num = StringHelper::sanitize($posted_data['order_num']);
        $order_date = StringHelper::sanitize($posted_data['order_date']);
        $plant_id = StringHelper::sanitize($posted_data['plant_name']);
        $timestamp = \Carbon\Carbon::now();
        $enq_ordered = true;
        $job_nums = []; // may be required in future
        $order_copy = $job_nums_str = "";
    // insert order data & upload order copy
        if(isset($posted_data['order_copy'])){
            $extension = $request->file('order_copy')->getClientOriginalExtension();
            $request->file('order_copy')->move($order_copy_path, $order_num.".".$extension);
            $order_copy = $order_num;
        }
        $order_data = array(
            'order_num' => $order_num,
            'order_date' => $order_date,
            'order_copy' => $order_copy,
            'plant_id' => $plant_id,
            'quotation_nos' => implode(",", $posted_data['add_order_for']),
            'created_at' =>  $timestamp,
            'updated_at' => $timestamp
        );
        $order_id = Order::insertGetId($order_data);
    // update order_id in enquiry table
        /* redundant since imploded quotation_nos already saved in orders table
        foreach($posted_data['add_order_for'] as $q_c=>$quotation_no){
            $enq_ord_upd = DB::table('enquiry')->where('quotation_no', $quotation_no)->update(['order_id' => $order_id]);
            if(!$enq_ord_upd){ $enq_ordered = false; }
        }*/
    // insert jobs data
        $jobs_data = array();
        foreach($posted_data['order_products'] as $pn => $pic){
            $this_job_no = date('y')."/".++$job_count;
            $temp_pd = new Jobs;
            $temp_pd->job_num = $this_job_no;
            $temp_pd->section = StringHelper::sanitize($posted_data['order_product_section-'.$pic]);
            $temp_pd->make = StringHelper::sanitize($posted_data['order_product_make-'.$pic]);
            $temp_pd->product_item_code = StringHelper::sanitize($pic);
            $temp_pd->description = StringHelper::sanitize($posted_data['order_product_name-'.$pic]);
            if(isset($posted_data['order_product_drawing_new-'.$pic])){
                $temp_pd->drawing_no = implode(",", $posted_data['order_product_drawing_new-'.$pic]);
            }
            $temp_pd->product_quantity = StringHelper::sanitize($posted_data['order_product_quantity-'.$pic]);
            $temp_pd->product_rate = StringHelper::sanitize($posted_data['order_product_rate-'.$pic]);
            $temp_pd->discount = StringHelper::sanitize($posted_data['order_product_discount-'.$pic]);
            $temp_pd->due_date = StringHelper::sanitize($posted_data['order_product_due_date-'.$pic]);
            $temp_pd->delivery_date = StringHelper::sanitize($posted_data['order_product_delivery_date-'.$pic]);
            array_push($jobs_data, $temp_pd);
            array_push($job_nums, $this_job_no);
            $job_nums_str .= "<code>".$this_job_no."</code>, ";
        }
        $jobs_res = Order::findOrFail($order_id)->addJobs()->saveMany($jobs_data);
    // upload new drawings
        $tot_new_dr = intval($posted_data['tot_new_dr']);
        for($c=1; $c<=$tot_new_dr; $c++){
            if(isset($posted_data['client_drawing_'.$c])){
                $drawing_num = StringHelper::sanitize($posted_data['client_drawing_number_'.$c]);
                $extension = $request->file('client_drawing_'.$c)->getClientOriginalExtension();
                $request->file('client_drawing_'.$c)->move($new_drawing_path, $drawing_num.".".$extension);
            }
        }
    // send inserted job numbers to client
        $job_nums_str = rtrim($job_nums_str, ", ");
        if($order_id && $jobs_res && $enq_ordered){
            Alert::success('The job numbers for added products are <b>'.$job_nums_str.'</b>.', 'Order Successfully Added!')->html()->persistent('Ok');
        }
        return redirect('/order/create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
         $query_fetch = DB::table('jobs')->select('status', 'comment', 'other_attachment')->where('job_id', '=', $id)->get();
         echo json_encode($query_fetch[0]);
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

        $edited_data = $request->all();
       // dd($edited_data);

        $timestamp = \Carbon\Carbon::now();

        if($edited_data['update_type'] == 'edit_order')
        {
        	if(isset($edited_data['job_status']))
	        {
	        	$job_status = StringHelper::sanitize($edited_data['job_status']);
	        }
	        else
	        {
	        	$job_status = '';
	        }

	        if(isset($edited_data['job_comment']))
	        {
	        	$job_comment = StringHelper::sanitize($edited_data['job_comment']);
	        }
	        else
	        {
	        	$job_comment = '';
	        }

	        $job_num = $edited_data['job_num'];

	        $order_path = 'documents/orders/';
	        $other_documents_path = $order_path.'jobs/other_attachments/'.$job_num.'/';
	        if(!file_exists($other_documents_path)){ mkdir($other_documents_path, 0755, true); };


	        if($request->file('job_documents')){
	        	$uploadedName = "";
	            //$extension = $request->file('job_documents')->getClientOriginalExtension();
	          //  $request->file('job_documents')->move($other_documents_path, $edited_data['job_documents']);
	            //$order_copy = $order_num;
	        	 foreach($request->file('job_documents') as $media)
		        {
		            if(!empty($media))
		            {
		            $docName = $media->getClientOriginalName();
					$temp = $media->move($other_documents_path, $docName);
					$uploadedName .= $other_documents_path.$docName.',';
		            }
		        }

		        $uploadedName = rtrim($uploadedName);
	        }
	        else
	        {
	        	$query_fetch = DB::table('jobs')->select('other_attachment')->where('job_id', '=', $id)->get();
	        	$uploadedName = $query_fetch[0]->other_attachment;
	        }

	        DB::table('jobs')->where('job_id', $id)->update(['status'=>$job_status, 'comment'=>$job_comment, 'other_attachment'=>$uploadedName, 'updated_at'=>$timestamp]);
	        Alert::success('The job number '.$job_num, 'Job Successfully Updated!')->html()->persistent('Ok');
	        return redirect('/order/');
        }

        else
        {
        	if(isset($edited_data['date_dispatch']))
	        {
	        	$date_dispatch = $edited_data['date_dispatch'];
	        }
	        else
	        {
	        	$date_dispatch = '';
	        }

	        if(isset($edited_data['lr_number']))
	        {
	        	$lr_number = $edited_data['lr_number'];
	        }
	        else
	        {
	        	$lr_number = '';
	        }

	        $job_num = $edited_data['job_num'];

	        $lr_path = 'documents/orders/lr_copy/';
	        if(!file_exists($lr_path)){ mkdir($lr_path, 0755, true); };


	        if($request->file('lr_copy')){
	            $lr_no = $request->file('lr_copy')->getClientOriginalName();
				$temp = $request->file('lr_copy')->move($lr_path, $lr_no);
	           
	        }
	        else
	        {
	        	$lr_no = "";
	        }

	        DB::table('jobs')->where('job_id', $id)->update(['delivery_date'=>$date_dispatch, 'lr_no'=>$lr_number, 'lr_copy'=>$lr_no, 'invoice_status'=>1, 'updated_at'=>$timestamp]);
	        Alert::success('The job number '.$job_num, 'Job Successfully Updated!')->html()->persistent('Ok');
	        return redirect('/order/completed');
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

    /**
     * Create an invoice for the given enquiry/order number
     *
     * @param  string  $id
     * @return Document in xlsx/xls format
     */
    public function generateInvoice()
    {
        //
        return view('generate-invoice');
    }

    public function getQuotationData(Request $request){
        $quotation_no = $request->all()['quotation_no'];
        DB::connection()->enableQueryLog();
        $query = DB::table('products as pr')->leftJoin('enquiry as en', 'en.enquiry_no', 'pr.enquiry_no')->leftJoin('plants as pl', 'pl.plant_id', 'en.plant_id')->join('clients as cl', 'cl.client_id', 'pl.client_id')->where('en.quotation_no', $quotation_no);
        $products = $query->get(['pr.product_name', 'pr.product_item_code', 'pr.drawing_no', 'pr.product_quantity', 'pr.product_rate']);
        $result_array = $query->select('cl.client_name', 'pl.client_id', 'pl.plant_name', 'pl.plant_id')->first();
        $queries = DB::getQueryLog();
        // return json_encode($queries);
        $result_array = json_decode(json_encode($result_array), true);
        $result_array['products'] = json_decode(json_encode($products), true);
        return json_encode($result_array);
    }
}
