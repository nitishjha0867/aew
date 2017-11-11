@extends('layouts.app')

@section('page_title', 'AEW | Add Enquiry')

@section('include_css')
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/select2.min.css') }}">
@endsection

@section('internal_css')
.label-control{margin-top:1em;}
.form-group1{margin:15px 0;}
#add_client_drawing{margin-right:20px;}
#add_product{margin-right:20px;margin-bottom:20px}
#add_aewdrawing{margin-right:20px;margin-bottom:20px}
#add_costsheet{margin-right:20px;margin-bottom:20px}
#remove_client_drawing{margin-right:25px; display:none;}
#remove_product{margin-right:25px; display:block;}
#remove_aewdrawing{margin-right:25px; display:none;}
#remove_costsheet{margin-right:25px; display:none;}
input[type=file] {
    display: block;
    /* border: 1px solid black; */
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
#page-wrapper{background-color:#F3F3F4;}
div[class^="product_details"]{
  	padding-bottom: 15px;
  	display: inline-block;
}
div[class^="product_details"]:not(.product_details1){ border-top: 1px solid #eee; }

.select2-dropdown{
	border: 1px solid #e9e9e9;
	border-radius: 0;
}
.select2-container{margin: 6px 0; width: 100% !important;}
.select2-container--default .select2-selection--multiple{
	border: 1px solid #e9e9e9;
	border-radius: 0;
	min-height: 36px;
}
.select2-container--default.select2-container--focus .select2-selection--multiple{
	border: 1px solid #e9e9e9;
}
.select2-container--default .select2-search--inline .select2-search__field {
    font-size: 0.9em;
    color: #999;
    padding: 0.1em .5em;
    font-weight: normal;
}
input[readonly], select[disabled] {
     cursor: not-allowed;
}

@endsection
@php
$enquiry_no = $edit_data[0]['enquiry_no'];

$client_drawing = explode(',', $edit_data[0]['att_client_drawing_path']);

$client_drawing_no = explode(',', $edit_data[0]['att_client_drwaing_no']);

$no_client_drawing = sizeof($client_drawing);
//dd($edit_data[0]);
$product_id = explode(',',$edit_data[0]['product_id']);
$product_name = explode(',',$edit_data[0]['product_name']);
$product_item_code = explode(',',$edit_data[0]['product_item_code']);
$product_quantity = explode(',',$edit_data[0]['product_quantity']);
$product_rate = explode(',',$edit_data[0]['product_rate']);
$product_unit_id = explode(',',$edit_data[0]['product_unit_id']);
$product_drawing_no = explode(',',$edit_data[0]['product_drawing_no']);

$product_unit = array(1=>"NO", 2=>"SET");

$att_cost_sheet_path = explode(',', $edit_data[0]['att_cost_sheet_path']);


$att_aew_drawing_path = explode(',', $edit_data[0]['att_aew_drawing_path']);

$att_enquiry_mail_path =  $edit_data[0]['att_enquiry_mail_path'];

@endphp
@section('page_content')
	<div class="panel-group" id="accordion">
	  <div class="panel">
	    <a data-toggle="collapse" data-parent="#accordion" href="#">
	      <div class="panel-heading">
	        <h4 class="panel-title">
	          <i class="fa fa-plus-square"></i> Add Enquiry
	        </h4>
	      </div>
	    </a>
	    <div id="panel_add_client_details" class="panel-collapse collapse in">
	      <div class="panel-body">
	      	<div class="col-md-10 col-md-offset-1">
				<form action="/add-enquiry/{{$edit_data[0]['sr_no']}}" method="POST" id="add_enquiry_form" class="form-inline" enctype="multipart/form-data">
				{{ csrf_field() }}
					<input type="hidden" name="_method" value="PUT">
					<input type="hidden" name="update_type" value="editted">
					{{-- <div class="col-md-10 form-group form-group1 col-md-offset-1">
						<label class="col-md-4 label-control" for="quotation_no">AEW Quotation No.</label>
						<div class="col-md-8">
							<input id="quotation_no" type="text" name="quotation_no" value="@php echo date('Y').'-AEW-'.sprintf('%04d', ++$today_entries);  @endphp" class="form-control col-md-8" placeholder="AEW Quotation No." title="Aew Quotation No. for future reference" autocomplete="off" readonly style="cursor: not-allowed;">
						</div>
					</div> --}}
					<div class="col-md-10 form-group form-group1 col-md-offset-1">
						<label class="col-md-4 label-control" for="client_name">Client Name</label>
						<div class="col-md-8">
							<input type="text" name="client_name" id="client_name" data-ajax="client1" value="{{$edit_data[0]['cli_client_name']}}" placeholder="Client Name" title="Client Name" required="" readonly>
						</div>
					</div>
					
					<div class="col-md-10 form-group form-group1 col-md-offset-1">
						<label class="col-md-4 label-control" for="plant_name">Plant Name</label>
						<div class="col-md-8">
							<select name="plant_name" id="plant_name" data-ajax="plant1" disabled>
								<option value='' disabled>Select Plant</option>
								<option value="{{$edit_data[0]['cli_plant_name']}}" selected>{{$edit_data[0]['cli_plant_name']}}</option>
							</select>
						</div>
					</div>
					
					<!--<div class="col-md-10 form-group form-group1 col-md-offset-1">
						<label class="col-md-4 label-control" for="enquiry_no">Enquiry No.</label>
						<div class="col-md-8">
							<input type="text" name="enquiry_no" id="enquiry_no" value="" placeholder="Enquiry No" title="Enquiry No">
						</div>
					</div>-->
					
					<div class="col-md-10 form-group form-group1 col-md-offset-1 client_drawing_div1">
						<label class="col-md-4 label-control">Client Drawing(s)</label>
						<!--<div class="col-md-8">
							<input type="file" name="client_drawing_1">
							<p class="help-block"><input type="text" name="client_drawing_number_1" placeholder="Drawing No." class="drawing_numbers"></p>
						</div>-->
						
						<div class="col-md-8">
						@for($i = 0; $i < sizeof($client_drawing); $i++)
							<div class="client_drawing_{{$i}}">
								<div class="col-md-8 file_download">
									<a href='{{ URL::asset("documents/$enquiry_no")."/Client Drawing/".$client_drawing[$i] }}' download>{{ $client_drawing_no[$i] }}</a>
								</div>
								<div class="col-md-8 file_edit hidden">
									<input type="file" name="client_drawing_path_{{$i}}">
									
									<input type="text" class="drawing_numbers" name="client_drawing_no_{{$i}}" value="{{$client_drawing_no[$i]}}">
								</div>
								<div class="col-md-2"><button type="button" class="btn btn-primary edit_client_drawing" data-edit="{{$i}}">Edit</button></div>
								<div class="col-md-2"><button type="button" class="btn btn-primary delete_client_drawing" data-delete="{{$i}}">Delete</button></div>
							</div>
						@endfor
						</div>
						<input type="hidden" class="edited_client_drawing" name="edited_client_drawing" value="">
						<input type="hidden" name="deleted_client_drawing" class="deleted_client_drawing" value="">
					</div>
					<div class="col-md-10 form-group form-group1 col-md-offset-1">
						<button type="button" class="btn btn-submit pull-right" id="add_client_drawing"><i class="fa fa-plus"></i> Add Client Drawing</button>
						<button type="button" class="btn btn-submit pull-right" id="remove_client_drawing"><i class="fa fa-times"></i> Remove</button>
					</div>
					<input type="hidden" name="counter_for_drawing_no" id="counter_for_drawing_no" value="{{sizeof($client_drawing)}}">
					
					@for($i = 0; $i < sizeof($product_id); $i++)
					<div class="product_details{{$i}}">
						<div class="col-md-10 form-group form-group1 col-md-offset-1">
							<label class="col-md-4 label-control"><u>Product <span class="product_count">{{$i+1}}</span></u> Item Code & Name</label>
							<div class="col-md-4">
								<input type="text" name="item_code{{$i}}" value="{{$product_item_code[$i]}}" placeholder="Item Code" title="Item Code">
							</div>
							<div class="col-md-4">
								<input type="text" name="product_name{{$i}}" value="{{$product_name[$i]}}" placeholder="Product Name" title="Product Name">
							</div>
						</div>
						<div class="col-md-10 form-group form-group1 col-md-offset-1">
							<label class="col-md-4 label-control"><u>Product <span class="product_count">{{$i+1}}</span></u> Quantity & Unit</label>
							
							<div class="col-md-2">
								<input type="text" name="product_quantity{{$i}}" value="{{$product_quantity[$i]}}" placeholder="Quantity" title="Product Quantity">
							</div>
							<div class="col-md-2">
								<select name="product_unit{{$i}}" title="Unit">
									@foreach ($clients_index_data['unit_data'] as $data)
										@if ($data->unit_id!="0") 
											@if ($product_unit_id[$i] == 	$data->unit_id)<option selected value="{{ $data->unit_id }}">{{ $data->unit_name }}(S)</option> 
											@else
												<option value="{{ $data->unit_id }}">{{ $data->unit_name }}(S)</option> 
											@endif
										@endif
									@endforeach
								</select>
							</div>

							<div class="col-md-4 prod_draw_tr">
								<select name="product_drawing{{$i}}[]"  title="Select drawing number for this product" multiple>
							
								</select>
							</div>
							{{-- <div class="col-md-4">
								<input type="text" name="product_price{{$i}}" value="" placeholder="Price/Peice" title="Price/Peice">
							</div> --}}
						</div>
						<button type="button" class="btn btn-submit pull-right remove_product" data-delete="{{$i}}"><i class="fa fa-times"></i> Remove</button>
						
						<input type="hidden" name="product_id{{$i}}" class="product_id" value="{{$product_id[$i]}}">
						
					</div>
					@endfor
					<div class="col-md-10 form-group form-group1 col-md-offset-1">
						<button type="button" class="btn btn-submit pull-right" id="add_product"><i class="fa fa-plus"></i> Add Product</button>
						<!--<button type="button" class="btn btn-submit pull-right" id="remove_product"><i class="fa fa-times"></i> Remove</button>-->
					</div>
					<input type="hidden" name="counter_for_products" id="counter_for_products" value="{{sizeof($product_id)}}">
					
					<input type="hidden" name="deleted_products" id="deleted_products">
					
					{{-- <div class="col-md-10 form-group form-group1 col-md-offset-1">
						<label class="col-md-4 label-control">Total Order Value</label>
						<div class="col-md-8">
							<input type="text" name="total_order_value" value="" placeholder="Total Order Value" title="Total Order Value">
						</div>
					</div> --}}
					
					{{-- <div class="col-md-10 form-group form-group1 col-md-offset-1">
						<label class="col-md-4 label-control">Negotiated Rate</label>
						<div class="col-md-8">
							<input type="text" name="negotiated_rate" value="" placeholder="Negotiated Rate" title="Negotiated Rate">
						</div>
					</div>
					
					<div class="col-md-10 form-group form-group1 col-md-offset-1">
						<label class="col-md-4 label-control">Previous/Lowest Rate</label>
						<div class="col-md-8">
							<input type="text" name="previous_rate" value="" placeholder="Previous/Late Rate" title="Previous/Lowest Rate">
						</div>
					</div> --}}
					
					<div class="col-md-10 form-group form-group1 col-md-offset-1">
						<label class="col-md-4 label-control">Enqiry Date</label>
						<div class="col-md-8">
							<input name="enquiry_date" placeholder="Enqiry Date" value="{{$edit_data[0]['enquiry_date']}}" title="Enquiry Date" class="date_picker">
						</div>
					</div>
					
					<div class="col-md-10 form-group form-group1 col-md-offset-1">
						<label class="col-md-4 label-control">Due Date</label>
						<div class="col-md-8">
							<input name="due_date" placeholder="Due Date" value="{{$edit_data[0]['due_date']}}" title="Due Date" class="date_picker">
						</div>
					</div>
					
					<div class="col-md-10 form-group form-group1 col-md-offset-1">
						<label class="col-md-4 label-control">Contact Person</label>
						<div class="col-md-8">
							<select name="contact_person" id="contact_person" >
							 	<option value="" disabled>Contact Person</option>
								<option selected value="{{$edit_data[0]['cli_person_name']}}">{{$edit_data[0]['cli_person_name']}}</option>
							</select>
						</div>
					</div>
					<div class="col-md-10 form-group form-group1 col-md-offset-1 cost_sheet_div1">
						<label class="col-md-4 label-control">Cost Sheet(s)</label>
						<div class="col-md-8">
							@for($i = 0; $i < sizeof($att_cost_sheet_path); $i++)
								<div class="cost_sheet_{{$i}}">
									<div class="col-md-8 file_download" style="margin:10px 0">
										<a href='{{ URL::asset("documents/$enquiry_no")."/Cost Sheet/".$att_cost_sheet_path[$i] }}' download>{{ $att_cost_sheet_path[$i] }}</a>
									</div>
									
									@if(!empty($att_cost_sheet_path[$i]))
										<button type="button" class="btn btn-submit pull-right remove_costsheet" data-delete="{{$i}}"><i class="fa fa-times"></i> Remove</button>
									@endif
								</div>
							@endfor
						</div>
						<input type="hidden" id="counter_for_costsheet" name="counter_for_costsheet" value="{{sizeof($att_cost_sheet_path)}}">
						<input type="hidden" id="deleted_costsheet" name="deleted_costsheet" value="">
						<div class="col-md-10 form-group form-group1 col-md-offset-1">
							<button type="button" class="btn btn-submit pull-right" id="add_costsheet"><i class="fa fa-plus"></i> Add Cost Sheet</button>
							<!--<button type="button" class="btn btn-submit pull-right" id="remove_costsheet"><i class="fa fa-times"></i> Remove</button>-->
						</div>
					</div>
					
					
					<div class="col-md-10 form-group form-group1 col-md-offset-1 cost_sheet_div1">
						<label class="col-md-4 label-control">AEW Drawing(s)</label>
						<div class="col-md-8">
							@for($i = 0; $i < sizeof($att_aew_drawing_path); $i++)
							<div class="aew_drawing_{{$i}}">
								<div class="col-md-8 file_download" style="margin:10px 0">
									<a href='{{ URL::asset("documents/$enquiry_no")."/AEW Drawing/".$att_aew_drawing_path[$i] }}' download>{{ $att_aew_drawing_path[$i] }}</a>
								</div>
								
								@if(!empty($att_aew_drawing_path[$i]))
									<button type="button" class="btn btn-submit pull-right remove_aewdrawing" data-delete="{{$i}}"><i class="fa fa-times"></i> Remove</button>
								@endif
							</div>
							@endfor
						</div>
						<input type="hidden" id="counter_for_aewdrawing" name="counter_for_aewdrawing" value="{{sizeof($att_aew_drawing_path)}}">
						<input type="hidden" id="deleted_aewdrawing" name="deleted_aewdrawing" value="">
						<div class="col-md-10 form-group form-group1 col-md-offset-1">
							<button type="button" class="btn btn-submit pull-right" id="add_aewdrawing"><i class="fa fa-plus"></i> Add AEW Drawing</button>
							<!--<button type="button" class="btn btn-submit pull-right" id="remove_costsheet"><i class="fa fa-times"></i> Remove</button>--->
						</div>
					</div>
					
					<!--<div class="col-md-10 form-group form-group1 col-md-offset-1 aew_drawing_div1">
						<label class="col-md-4 label-control">AEW Drawing(s)</label>
						<div class="col-md-8">
							<input type="file" name="aew_drawing_upload1">
						</div>
					</div>
					<input type="hidden" id="counter_for_aewdrawing" name="counter_for_aewdrawing" value="1">
					<div class="col-md-10 form-group form-group1 col-md-offset-1">
						<button type="button" class="btn btn-submit pull-right" id="add_aewdrawing"><i class="fa fa-plus"></i> Add More AEW Drawing</button>
						<button type="button" class="btn btn-submit pull-right" id="remove_aewdrawing"><i class="fa fa-times"></i> Remove</button>
					</div>-->
					
					
					<div class="col-md-10 form-group form-group1 col-md-offset-1 cost_sheet_div1">
						<label class="col-md-4 label-control">Enquiry Mail Copy</label>
						<div class="col-md-8">
							<div class="enquiry_mail">
								<div class="col-md-8 file_download" style="margin:10px 0">
										<a href='{{ URL::asset("documents/$enquiry_no")."/".$att_enquiry_mail_path }}' download>{{ $att_enquiry_mail_path }}</a>
									</div>
									
									<button type="button" class="btn btn-submit pull-right remove_enquiry_mail"><i class="fa fa-times"></i> Remove</button>
							</div>
						</div>
						
						<div class='enquiry_mail_input hidden'>
							<input type='file' name='enquiry_mail'>
						</div>
						<input type="hidden" id="deleted_enquiry_copy" name="deleted_enquiry_copy" value="0">
					</div>
					
					<!--<div class="col-md-10 form-group form-group1 col-md-offset-1 enquiry_mail_div">
						<label class="col-md-4 label-control">Enquiry Mail Copy</label>
						<div class="col-md-8">
							<input type="file" name="enquiry_mail">
						</div>
					</div>-->
					<!--<input type="hidden" id="counter_for_aewdrawing" name="counter_for_aewdrawing" value="1">
					<div class="col-md-10 form-group form-group1 col-md-offset-1">
						<button type="button" class="btn btn-submit pull-right" id="add_enquirymail"><i class="fa fa-plus"></i> Add More AEW Drawing</button>
						<button type="button" class="btn btn-submit pull-right" id="remove_enquirymail"><i class="fa fa-times"></i> Remove Enquiry Mail Copy</button>
					</div>-->
					
					<div class="col-md-10 form-group form-group1 col-md-offset-1 text-center">
						<button class="btn btn-primary">Update Enquiry</button>
					</div>
				</form>
			</div>
	      </div>
	    </div>
	  </div>
	</div>
@endsection

@section('include_js')
  <script src="{{ URL::asset('js/typeahead.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('js/select2.full.min.js') }}"></script>
@endsection


{{-- typeahead scripts for client and plant name --}}
  @php 
    $clients_name_arr = [];
    foreach($clients_index_data['all_clients'] as $client_id => $client_name){
      $clients_name_arr[] = $client_name;
    }
  @endphp
  
@section('functions_js')
  	function ajaxLoad(_this, callbackFn1, callbackFn2 = false){
	  var this_val = $(_this).val();
	  if(this_val !== "" && this_val !== null){
	    var this_typ = $(_this).data("ajax");
	    switch(this_typ){
	      case 'client1':
	        if(window.clients.local.indexOf(this_val)>=0){
	          ajax_needed = true;
	        } break;
	    }
	    if(ajax_needed){
	      $('body').addClass("is_loading");
	      $.get("/manage-clients/"+this_typ+"/"+this_val, function(response){
	        callbackFn1(response, this_typ, callbackFn2);
	      }, "JSON");
	    } else {
	      console.warn("ajax not needed for "+this_typ);
	    }
	  }
	}

	function processDataAndSetOptions(data, type, callbackFn2){
	  switch(type){
	    case 'client1':
	      if(data.length !== 0){
	        var plant_opts = "<option value='' disabled selected>Select Contact Person</option>";
	        for(each in data){ plant_opts += `<option value="${data[each].plant_name}">${data[each].plant_name}</option>`; }
	        $('select#plant_name').html(plant_opts);
	      }
	      break;

	    case 'plant1':
	      if(data.contact_details.length !== 0){
	      	var contact_opts = "<option value='' disabled selected>Select Contact Person</option>";
		    data.contact_details.forEach(v=>{
		    	contact_opts += `<option value="${v.person_name}">${v.person_name}</option>`;
	    	});
	      	$('select#contact_person').html(contact_opts);
          }
	  	  break;
	  }
	  if(typeof callbackFn2 === "function"){ callbackFn2(); }
	  $('body').removeClass("is_loading");
	}
	
	function map_drawing_no(){
		var pr_dr_select2_opts = {placeholder: "Product Drawing Number(s)"};
		var drawing_numbers = $('input.drawing_numbers');		var prod_draw_opts = "";
		drawing_numbers.each((k,v)=>{ prod_draw_opts += `<option value="${v.value}">${v.value}</option>`; });
		$('select[name^="product_drawing"]').html(`<option value="" disabled selected>Product Drawing Number</option>${prod_draw_opts}`);
		
		$('select[name^="product_drawing"]').html(prod_draw_opts).select2(pr_dr_select2_opts);
	}
@endsection

  @section('document_ready')
  var pr_dr_select2_opts = {placeholder: "Product Drawing Number(s)"};
	$('#add_enquiry_form')[0].reset();

	window.clients = {!! json_encode($clients_name_arr) !!};
	  // constructs the suggestion engine
	  window.clients = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.whitespace,
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		local: clients
	  });
	  
	  $('input#client_name').typeahead({ hint: true, highlight: true, minLength: 1
  },{ name: 'clients', source: clients });
  
  $('#add_client_drawing').click(function(){
	  var temp_count = parseInt($('#counter_for_drawing_no').val());
	  temp_count_store = temp_count+1; //temp_count_store for further use to clone new sections
	  $('#counter_for_drawing_no').val(temp_count_store)
	  temp_count = temp_count-1;
	  temp_count++;
	  var  div_cloned = "<div class='client_drawing_"+temp_count+"'><div class='col-md-8 file_edit'><input type='file' name='client_drawing_path_"+temp_count+"'><input type='text' class='drawing_numbers' name='client_drawing_no_"+temp_count+"' value=''></div><div class='col-md-2'></div><div class='col-md-2'><button type='button' class='btn btn-primary delete_client_drawing' data-delete='"+temp_count+"'>Delete</button></div></div>";
	  
	  $(div_cloned).insertAfter('.client_drawing_'+(temp_count_store-2));
	
	
	
  });
  
  $('#remove_client_drawing').click(function(){
	  var temp_count = $('#counter_for_drawing_no').val();
	  if(temp_count > 1)
	  {
		  $('.client_drawing_div'+temp_count).remove();
		  temp_count--;
		  if(temp_count < 2)
		  {
			  $('#remove_client_drawing').css('display', 'none');
		  }
		  $('#counter_for_drawing_no').val(temp_count);
	  }
	  else
	  {
		  $('#remove_client_drawing').css('display', 'none');
	  }
  })
  
  
  $('body').on('click', '#add_product', function() {
	  var temp_count = parseInt($('#counter_for_products').val());
	  

	 /* if(temp_count > 1)
	  {
		  $('#remove_product').css('display', 'block');
	  }*/
	  
	  $('#counter_for_products').val(temp_count+1);
	  var div_cloned = $('.product_details'+(temp_count-1)).clone();

	  div_cloned.find(':input').val("");
	  div_cloned.find('.product_count').text(temp_count+1);
	  input = div_cloned.find('input');
	  input1 = input[0];
	  input2 = input[1];
	  input3 = input[2];
	  input4 = input[4];
	  
	  input1.name = (input1.name.slice(0, -1))+temp_count;
	  input2.name = (input2.name.slice(0, -1))+temp_count;
	  input3.name = (input3.name.slice(0, -1))+temp_count;
	  input4.name = (input4.name.slice(0, -1))+temp_count;
	  div_cloned.find('prod_draw_tr');
	  select = div_cloned.find('select');
	 select[0].name = (select[0].name.slice(0, -1))+temp_count;
	 
	var curr_dr_opts = $(select[1]).html();
	 $(select[1]).remove();

	 div_cloned.find('.prod_draw_tr').html(`<select name="product_drawing${temp_count}[]" title="Select drawing number for this product" multiple>${curr_dr_opts}</select>`);
	 div_cloned.find('.prod_draw_tr>select[name^="product_drawing"]').select2(pr_dr_select2_opts); // placeholder not working
	div_cloned.css('display', 'block');
	  div_cloned.removeClass('product_details'+(temp_count-1));
	  div_cloned.addClass('product_details'+temp_count);
	 $(div_cloned).insertAfter('.product_details'+(temp_count-1));
	
  });
  
 $('body').on('click', '.remove_product', function(){
		data_delete = $(this).attr('data-delete');
		temp_deleted = $('#deleted_products').val();
		temp_deleted = temp_deleted+data_delete+",";
		$('#deleted_products').val(temp_deleted);
		$(this).parent().fadeOut('slow');
  })
  
  
  $('#add_costsheet').click(function(){
	  
	  var temp_count = parseInt($('#counter_for_costsheet').val());
	  
	  temp_count = temp_count+1; //temp_count_store for further use to clone new sections

	  $('#counter_for_costsheet').val(temp_count)
	  temp_count = temp_count-1;
	  var  div_cloned = "<div class='cost_sheet_"+temp_count+"'><input type='file' name='cost_sheet_upload"+temp_count+"'><button type='button' class='btn btn-submit pull-right remove_costsheet' data-delete='"+temp_count+"'><i class='fa fa-times'></i> Remove</button></div>";
	  
	  $(div_cloned).insertAfter('.cost_sheet_'+(temp_count-1));
	  
  });
  
  $('body').on('click', '.remove_costsheet', function() {
	  var datadelete = $(this).attr('data-delete');	  
	  $('.cost_sheet_'+datadelete).fadeOut('slow');
	  var deleted_costsheet = $('#deleted_costsheet').val();
	  deleted_costsheet = deleted_costsheet+datadelete+",";
	  $('#deleted_costsheet').val(deleted_costsheet);
  })
  
  
    $('#add_aewdrawing').click(function(){
	  
	  var temp_count = parseInt($('#counter_for_aewdrawing').val());
	  
	  temp_count = temp_count+1; //temp_count_store for further use to clone new sections

	  $('#counter_for_aewdrawing').val(temp_count)
	  temp_count = temp_count-1;
	  var  div_cloned = "<div class='aew_drawing_"+temp_count+"'><input type='file' name='aew_drawing_upload"+temp_count+"'><button type='button' class='btn btn-submit pull-right remove_aewdrawing' data-delete='"+temp_count+"'><i class='fa fa-times'></i> Remove</button></div>";
	  
	  $(div_cloned).insertAfter('.aew_drawing_'+(temp_count-1));
	  
  });
  
  $('body').on('click', '.remove_aewdrawing', function() {
	  var datadelete = $(this).attr('data-delete');	  
	  $('.aew_drawing_'+datadelete).fadeOut('slow');
	  var deleted_aewdrawing = $('#deleted_aewdrawing').val();
	  deleted_aewdrawing = deleted_aewdrawing+datadelete+",";
	  $('#deleted_aewdrawing').val(deleted_aewdrawing);
  })
  
  $('body').on('click', '.remove_enquiry_mail', function() {
	  $('#deleted_enquiry_copy').val(1);
	  $('.enquiry_mail').fadeOut('slow');
	  $('.enquiry_mail_input').removeClass('hidden');

  })
  
	$('.date_picker').datetimepicker({
		format:"YYYY-MM-DD"
	});
	
	/*$('input[placeholder="Price/Peice"]').blur(function(event){
		var product_counter = $('#counter_for_products').val();
		order_value = 0;
		while(product_counter != 0)
		{
			order_value += ($('input[name="product_quantity'+product_counter+'"]').val()) * ($('input[name="product_price'+product_counter+'"]').val())
			product_counter--;
		}
		alert(order_value);
	});*/

	$(document).on("blur", 'input.drawing_numbers', function(){
		var drawing_numbers = $('input.drawing_numbers');
		var prod_draw_opts = "";
		drawing_numbers.each((k,v)=>{ prod_draw_opts += `<option value="${v.value}">${v.value}</option>`; });
		$('select[name^="product_drawing"]').html(prod_draw_opts).select2(pr_dr_select2_opts);
	});

	{{-- ajax bindings --}}
	$('#client_name, #plant_name').on('blur change', function(){
	  ajaxLoad(this, processDataAndSetOptions);
	});
	
	
	$('.edit_client_drawing').click(function(){
		var edited_temp_val = $('.edited_client_drawing').val();
		edited_temp_val = edited_temp_val+$(this).attr('data-edit')+",";
		$('.edited_client_drawing').val(edited_temp_val);
		var temp_parent = $(this).parent().parent();
		temp_parent_class = temp_parent.attr('class');
		$('.'+temp_parent_class +' .file_download').addClass('hidden');
		$('.'+temp_parent_class +' .file_edit').removeClass('hidden');
		
		
	})
	
	$('body').on('click', '.delete_client_drawing', function() {
		
		var deleted_temp_val = $('.deleted_client_drawing').val();
		deleted_temp_val = deleted_temp_val+$(this).attr('data-delete')+",";
		$('.deleted_client_drawing').val(deleted_temp_val);
		
		var temp_parent = $(this).parent().parent();
		temp_parent_class = temp_parent.attr('class');

		$('.'+temp_parent_class).fadeOut('slow');
		
		//map_drawing_no();
	})
	
	@php
		$js_product_drawing_no = json_encode($product_drawing_no);
		echo "var js_product_drawing_no = ".$js_product_drawing_no.";";
	@endphp
	
	map_drawing_no();
	
	for(j = 0; j<js_product_drawing_no.length; j++)
	{
		$('select[name="product_drawing'+j+'[]"]').val([js_product_drawing_no[j]]).trigger('change');
	}
	
	
  
  @endsection
