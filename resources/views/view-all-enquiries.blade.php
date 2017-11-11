@extends('layouts.app')

@section('page_title', 'AEW | Enquiry Details')

@section('include_css')
@endsection

@section('internal_css')
.text-center *{text-align: center;}
th{background: #eee}
.table.mini_table td, .table.mini_table>tbody>tr>td, .table.mini_table>tbody>tr>th, .table.mini_table>tfoot>tr>td, .table.mini_table>tfoot>tr>th, .table.mini_table>thead>tr>td, .table.mini_table>thead>tr>th{ padding: 0px 5px !important; }
.active_qtn{ text-decoration: underline;{{-- font-weight: bold; --}} }
input[type=file] {
    display: block;
    padding: 10px;
    border: 1px solid #E9E9E9;
    font-size: 0.9em;
    width: 100%;
    outline: none;
    padding: 0.5em 1em;
    color: #999;
    background: #fff;
    margin-top: 0.5em;
    font-family: 'Muli-Regular';
}
#add_aewdrawing{margin-right:20px;margin-bottom:20px}
#add_costsheet{margin-right:20px;margin-bottom:20px}
#remove_aewdrawing{margin-right:25px; display:none;}
#remove_costsheet{margin-right:25px; display:none;}
label{margin-top:1em;}
.btn-submit{width:100%;}
@endsection

@php
	// each arrays
	$enquiry_details = $enquiry_data['enquiry_details'];
	$client_details = $enquiry_data['client_details'];
	$product_details = $enquiry_data['product_details'];
	$attachment_details = $enquiry_data['attachment_details'];
	// variable definition
	$ver_sep = $enquiry_data['ver_sep'];
	$enquiry_no = $enquiry_details['enquiry_no'];
	$sr_no = $enquiry_details['sr_no'];
	$quotation_no = $enquiry_details['quotation_no'];
	$lowest_rate = $enquiry_details['lowest_rate'];
	$quotation_file_name = $attachment_details['quotation_file_name'];
	$quotation_revisions = $enquiry_details['quotation_revisions'];
	$client_drawing_nos = explode(",", $attachment_details['client_drwaing_no']);
	$client_drawings_path = explode(",", $attachment_details['client_drawing_path']);
	// echo "<pre>"; print_r($client_drawing_nos); print_r($client_drawings_path); echo "</pre>"; dd("0");
	$client_drawings = array_combine($client_drawing_nos, $client_drawings_path);
	$cost_sheets = explode(",", $attachment_details['cost_sheet_path']);
	$aew_drawings = explode(",", $attachment_details['aew_drawing_path']);
	
	$lowest_rate_var = array();
	if($enquiry_details['lowest_rate'] != "")
	{	
		$lowest_rate_var = explode(",", $enquiry_details['lowest_rate']);
		$lowest_rate_size = sizeof($lowest_rate);
	}
	else{
		$lowest_rate_size = 0;
	}
	
	$enquiry_details['enquiry_submitted'] = (int)$enquiry_details['enquiry_submitted'];
	
@endphp
@section('page_content')
	<div class="panel-group" id="accordion">
	    <div class="panel">
	      <a data-toggle="collapse" data-parent="#accordion" href="#">
	        <div class="panel-heading">
	          <h4 class="panel-title">
	            <i class="fa fa-file-text-o"></i> Details of Enquiry <b>{{ $enquiry_no }}</b>
	          </h4>
	        </div>
	      </a>
	      <div id="panel_add_enquiries_bulk" class="panel-collapse collapse in">
	        <div class="panel-body">
	        	<table id="table_enquiry_details" class="table table-bordered table-hover mini_table">
	        		<caption>Enquiry Details</caption>
					<thead>
	        			<tr class="text-center">
		        			<th>Enquiry Number</th>
		        			<th>Enquiry Date</th>
		        			<th>Due Date</th>
		        			<th>Enquiry Submitted</th>
		        			<th>Total Order Value</th>
		        		</tr>
	        		</thead>
	        		<tbody>
	        			<tr class="text-center">
		        			<td>{{ $enquiry_no }}</td>
		        			<td>{{ $enquiry_details['enquiry_date'] }}</td>
	    	    			<td>{{ $enquiry_details['due_date'] }}</td>
	        				<td>{{ $enquiry_details['enquiry_submitted']===1?"Yes":"No" }}</td>
	        				<td>{{ $enquiry_details['total_order_value'] }}</td>
	        			</tr>
	        		</tbody>
	        	</table>
	        	<table id="table_client_details" class="table table-bordered table-hover mini_table">
	        		<caption>Client Details</caption>
	        		<thead>
	        			<tr class="text-center">
	        				<th>Client Name</th>
	        				<th>Plant Name</th>
	        				<th>Plant Location</th>
	        				<th>Contact Person Name</th>
	        				<th>Contact Person Mobile</th>
	        				<th>Contact Person Email</th>
	        			</tr>
	        		</thead>
	        		<tbody>
	        			<tr class="text-center">
	        				<td>{{ $client_details['client_name'] }}</td>
	        				<td>{{ $client_details['plant_name'] }}</td>
	        				<td>{{ $client_details['plant_state'] }}</td>
	        				<td>{{ $client_details['person_name'] }}</td>
	        				<td>{{ $client_details['person_mobile'] }}</td>
	        				<td><a href="mailto:{{$client_details['person_email']}}">{{ $client_details['person_email'] }}</a></td>
	        			</tr>
	        		</tbody>
	        	</table>
				<form action="/add-enquiry/{{$sr_no}}" method="POST" name="upload_drawings" id="upload_drawings" enctype="multipart/form-data">
							{{ csrf_field() }}
					<table id="table_product_details" class="table table-bordered table-hover mini_table">
						<caption>Product Details</caption>
						<thead>
							<tr>
								<th>Sr. No.</th>
								<th>Item Code</th>
								<th>Product Name</th>
								<th>Quantity</th>
								<th>Rate</th>
								<th>Drawing Number</th>
								@if($enquiry_details['enquiry_submitted'] === 1)
									<th>Lowest Rate</th>
								@endif
								
							</tr>
						</thead>
						<tbody>
							@php
										$var_btn = 0;
									@endphp
							@foreach($product_details as $key=>$value)
								<tr>
									<td>{{ ++$key }}</td>
									<td>{{ $value['product_item_code'] }}</td>
									<td>{{ $value['product_name'] }}</td>
									<td>{{ $value['product_quantity'] }}</td>
									<td>{{ $value['product_rate'] }}</td>
									<td>{{ $value['drawing_no']==="" ? "No Drawing" : $value['drawing_no'] }}</td>
									@if($enquiry_details['enquiry_submitted'] === 1)
									@php
										$var_btn = 0;
									@endphp
									<td>
										@if(array_key_exists($key-1, $lowest_rate_var))
										{{ $lowest_rate_var[$key-1]}}
										<input type="hidden" class="form-control" style="margin:5px 0;" value="{{$lowest_rate_var[$key-1]}}" name="lowest_rate{{$key}}">
										@else
										@php
											$var_btn = 1;
										@endphp
										<input type="text" class="form-control" style="margin:5px 0;" name="lowest_rate{{$key}}">
										@endif
									</td>
									@endif
								</tr>
							@endforeach
								@if($var_btn == 1)
									<tr><td colspan=6></td><td class="text-center"><button class="btn btn-submit">Submit</button></td></tr>
								@endif
						</tbody>
						
					</table>
					<input name="_method" type="hidden" value="PUT">
					<input name="input_count" type="hidden" value="{{sizeof($product_details)}}">
					<input name="update_type" type="hidden" value="submitted">
					<input name="enquiry_no" type="hidden" value="{{$enquiry_no}}">
				</form>
				
	        	<table id="table_attachment_details" class="table table-bordered table-hover mini_table">
	        		<caption>Attachment Details</caption>
	        		{{-- @foreach($attachment_details as $key=>$value) <tr><td>{{$key}}</td><td>{{$value}}</td> @endforeach --}}
	        		<tbody>
	        			<tr>
	        				<th>Document Name</th>
	        				<th>Document Number (Click to Download)</th>
	        			</tr>
	        			<tr>
	        				<td><b>Quotation</b></td>
	        				<td>
	        					@if($quotation_no!=="")
		        					@php
		        						for($qr=$quotation_revisions; $qr>=0; $qr--){
			        						$sfx = $qr===0 ? "" : $ver_sep.$qr;
			        						$exp_q_fn = explode(" - ", $quotation_file_name);
			        						$this_q_no = substr($quotation_no, 0, 12).$sfx;
			        						$this_q_fn = "Quotation ".$this_q_no." - ".$exp_q_fn[1];
			        						$bold_if_active = $qr===$quotation_revisions ? "active_qtn" : "";
			        				@endphp
											<a href='{{ URL::asset("documents/$enquiry_no")."/".$this_q_fn }}' class="{{ $bold_if_active }}" download>{{ $this_q_no }}</a><br>
									@php } @endphp
								@else
									- Quotation not generated yet -
								@endif
	        				</td>
	        			</tr>
	        			<tr>
	        				<td><b>Client Drawing</b></td>
	        				<td>
								@foreach($client_drawings as $drawing_num => $drawing_path)
	        						<a href='{{ URL::asset("documents/$enquiry_no")."/Client Drawing/".$drawing_path }}' download>{{ $drawing_num }}</a><br>
	        					@endforeach
	        				</td>
	        			</tr>
	        			<tr>
	        				<td><b>Cost Sheet</b></td>
	        				<td>
								@foreach($cost_sheets as $key => $cost_sheet)
									<a href='{{ URL::asset("documents/$enquiry_no")."/Cost Sheet/".$cost_sheet }}' download>{{ preg_replace('/.docx|.doc|.xlsx|.xls|.pdf/', '', $cost_sheet) }}</a><br>
								@endforeach
	        				</td>
	        			</tr>
	        			<tr>
	        				<td><b>AEW Drawing</b></td>
	        				<td>
								@foreach($aew_drawings as $key => $aew_drawing)
									<a href='{{ URL::asset("documents/$enquiry_no")."/AEW Drawing/".$aew_drawing }}' download>{{ preg_replace('/.docx|.doc|.xlsx|.xls|.pdf/', '', $aew_drawing) }}</a><br>
								@endforeach
	        				</td>
	        			</tr>
	        			<tr>
	        				<td><b>Enquiry Mail Copy</b></td>
	        				<td>
	        					<a href='{{ URL::asset("documents/$enquiry_no")."/".$attachment_details['enquiry_mail_path'] }}' download>{{ preg_replace('/.docx|.doc|.xlsx|.xls|.pdf/', '', $attachment_details['enquiry_mail_path']) }}</a>
	        				</td>
	        			</tr>
	        		</tbody>
	        	</table>
	        	<!--@if($quotation_no != "")
					<div class="page_form col-md-6 col-md-offset-3" style="border:1px solid #000; padding:20px 0;">
						<h3 class="text-center">Lowest Rate</h3>
						<form action="/add-enquiry/{{$sr_no}}" method="POST" name="lowest_rate" id="lowest_rate">
							{{ csrf_field() }}
							<div class="form-group">
								<div class="col-md-12">
									<div class="col-md-8">
										<div class="col-md-10 col-md-offset-1">
											<input type="text" class="form-control" name="lowest_rate" value="{{$lowest_rate}}" placeholder="Enter Rate">
										</div>
									</div>
									<div class="col-md-4">
										<button class="btn btn-primary" type="submit" id="negotiated_rate_submit">Submit</button>
									</div>
								</div>
							</div>
							<input name="_method" type="hidden" value="PUT">
							<input name="update_type" type="hidden" value="submitted">
						</form>
					</div>-->
					<div class="clearfix"></div>

					<!--<div class="page_form col-md-8 col-md-offset-2" style="border:1px solid #000; padding:20px 0;">
						<form action="/add-enquiry/{{$sr_no}}" method="POST" name="upload_drawings" id="upload_drawings" enctype="multipart/form-data">
							{{ csrf_field() }}
							<h3 class="text-center" style="margin-bottom:20px;">Upload Drawings</h3>
							<div class="col-md-10 form-group form-group1 col-md-offset-1 cost_sheet_div1">
								<label class="col-md-4 label-control">Cost Sheet(s)</label>
								<div class="col-md-8">
									<input type="file" name="cost_sheet_upload1">
								</div>
							</div>
							<input type="hidden" id="counter_for_costsheet" name="counter_for_costsheet" value="1">
							<div class="col-md-10 form-group form-group1 col-md-offset-1">
								<button type="button" class="btn btn-submit pull-right" id="add_costsheet"><i class="fa fa-plus"></i> Add More Cost Sheet</button>
								<button type="button" class="btn btn-submit pull-right" id="remove_costsheet"><i class="fa fa-times"></i> Remove</button>
							</div>
							
							<div class="col-md-10 form-group form-group1 col-md-offset-1 aew_drawing_div1">
								<label class="col-md-4 label-control">AEW Drawing(s)</label>
								<div class="col-md-8">
									<input type="file" name="aew_drawing_upload1">
								</div>
							</div>
							<input type="hidden" id="counter_for_aewdrawing" name="counter_for_aewdrawing" value="1">
							<div class="col-md-10 form-group form-group1 col-md-offset-1">
								<button type="button" class="btn btn-submit pull-right" id="add_aewdrawing"><i class="fa fa-plus"></i> Add More AEW Drawing</button>
								<button type="button" class="btn btn-submit pull-right" id="remove_aewdrawing"><i class="fa fa-times"></i> Remove</button>
							</div>
							
							<div class="col-md-10 form-group form-group1 col-md-offset-1 enquiry_mail_div">
								<label class="col-md-4 label-control">Enquiry Mail Copy</label>
								<div class="col-md-8">
									<input type="file" name="enquiry_mail">
								</div>
							</div>
							
							<div class="col-md-10 form-group form-group1 col-md-offset-1 text-center" style="margin-top:20px;">
								<button class="btn btn-primary">Upload</button>
							</div>
							<input name="_method" type="hidden" value="PUT">
							<input name="update_type" type="hidden" value="pending">
							<input name="enquiry_no" type="hidden" value="{{$enquiry_no}}">
						</form>
					</div>-->
					
					@section('document_ready')
						$('#add_costsheet').click(function(){
							var temp_count = $('#counter_for_costsheet').val();
							temp_count++;
							if(temp_count > 1)
							{
							  $('#remove_costsheet').css('display', 'block');
							}
							$('#counter_for_costsheet').val(temp_count);
							var div_cloned = $('.cost_sheet_div'+(temp_count-1)).clone();
							div_cloned.find(':input').val("");
							input = div_cloned.find('input');
							input1 = input[0];
							input1.name = (input1.name.slice(0, -1))+temp_count;
							div_cloned.removeClass('cost_sheet_div'+(temp_count-1));
							div_cloned.addClass('cost_sheet_div'+temp_count);
							$(div_cloned).insertAfter('.cost_sheet_div'+(temp_count-1));
						});


						$('#remove_costsheet').click(function(){
						var temp_count = $('#counter_for_costsheet').val();
						if(temp_count > 1)
						{
						  $('.cost_sheet_div'+temp_count).remove();
						  temp_count--;
						  if(temp_count < 2)
						  {
							  $('#remove_costsheet').css('display', 'none');
						  }
						  $('#counter_for_costsheet').val(temp_count);
						}
						else
						{
						  $('#remove_costsheet').css('display', 'none');
						}
						})
						
						
						$('#add_aewdrawing').click(function(){
						  var temp_count = $('#counter_for_aewdrawing').val();
						  temp_count++;
						  if(temp_count > 1)
						  {
							  $('#remove_aewdrawing').css('display', 'block');
						  }
						  $('#counter_for_aewdrawing').val(temp_count);
						  var div_cloned = $('.aew_drawing_div'+(temp_count-1)).clone();
						  div_cloned.find(':input').val("");
						  input = div_cloned.find('input');
						  input1 = input[0];
						  input1.name = (input1.name.slice(0, -1))+temp_count;
						  div_cloned.removeClass('aew_drawing_div'+(temp_count-1));
						  div_cloned.addClass('aew_drawing_div'+temp_count);
						 $(div_cloned).insertAfter('.aew_drawing_div'+(temp_count-1));
					  });
					  
					  
					  $('#remove_aewdrawing').click(function(){
						  var temp_count = $('#counter_for_aewdrawing').val();
						  if(temp_count > 1)
						  {
							  $('.aew_drawing_div'+temp_count).remove();
							  temp_count--;
							  if(temp_count < 2)
							  {
								  $('#remove_aewdrawing').css('display', 'none');
							  }
							  $('#counter_for_aewdrawing').val(temp_count);
						  }
						  else
						  {
							  $('#remove_aewdrawing').css('display', 'none');
						  }
					  })
						
					@endsection
					
				@endif
	        </div>
	      </div>
	    </div>
	</div>
@endsection