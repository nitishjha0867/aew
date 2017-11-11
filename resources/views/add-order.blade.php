@extends('layouts.app')

@section('page_title', 'AEW | Add Order')

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
	#remove_product{margin-right:25px; display:none;}
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
	.select2-container{margin: 6px 0; width: 150px !important;}
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
	    font-family: 'Muli-Regular';
	}
	.form-inline select.form-control{
		width: 100%;
	}
	button.order_remove_product>.fa{ color: #eee; }
	button.order_remove_product:hover>.fa, button.order_remove_product:focus>.fa{ color: #fff; }
	#table_product_details td{ position: relative; width: 120px !important; }
	.custom_checkbox{display:none}
	.custom_checkbox+label:before{
    content: "\f096";
    font-family: FontAwesome;
    position: absolute;
    cursor: pointer;
    font-size: 1.4em;
    left: 22px;
    top: 10px;
	}
	.custom_checkbox:checked+label:before{
    content: "\f046";
	}
	#products_wrapper{ display:none; }
	.no_lpadding{ padding-left: 0 !important; }
	.no_rpadding{ padding-right: 0 !important; }
	.no_padding{ padding: 0 !important; }
	.client_drawing_div {
    position: relative;
    display: inline-block;
    width: 100%;
	}
	button#remove_clone {
    display: none;
	}
	td span.select2{ padding:0;}
	td span{font-size: 1em; padding:0;}
	td .select2-container--default .select2-selection--multiple .select2-selection__choice{ color: #111; }
	#add_order_for+span.select2-container{
		width: 100% !important;
	}
@endsection

@section('head_js')
@endsection

@section('page_content')
	<div class="panel-group" id="accordion">
	  	<div class="panel">
		    <a data-toggle="collapse" data-parent="#accordion" href="#">
		      <div class="panel-heading">
		        <h4 class="panel-title">
		          <i class="fa fa-plus-square"></i> Add Order
		        </h4>
		      </div>
		    </a>
		    <div id="panel_add_client_details" class="panel-collapse collapse in">
		      	<div class="panel-body">
			      	<div class="col-md-12">
						<form action="/order" method="POST" id="add_enquiry_form" class="form-inline" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="col-md-10 form-group form-group1 col-md-offset-1">
								<label class="col-md-4 label-control" for="add_order_for">Add Order for</label>
								<div class="col-md-8">
									<select id="add_order_for" name="add_order_for[]" class="form-control" multiple>
	                  @foreach($quotation_nums as $id => $quotation_num)
											<option value="{{ $quotation_num->quotation_no }}">{{ $quotation_num->quotation_no }}</option>
										@endforeach
	                </select>
								</div>
							</div>
							<div class="col-md-10 form-group form-group1 col-md-offset-1">
								<label class="col-md-4 label-control" for="order_num">Purchase Order Number</label>
								<div class="col-md-8">
									<input type="text" name="order_num" id="order_num" value="" placeholder="Order Number" title="Order Number" required="">
								</div>
							</div>
							<div class="col-md-10 form-group form-group1 col-md-offset-1">
								<label class="col-md-4 label-control" for="order_date">Order Date</label>
								<div class="col-md-8">
									<input type="text" name="order_date" class="date_picker" id="order_date" value="" placeholder="Order Date" title="Order Date" required="">
								</div>
							</div>
							<div class="col-md-10 form-group form-group1 col-md-offset-1">
								<label class="col-md-4 label-control" for="client_name">Client Name</label>
								<div class="col-md-8">
									<select name="client_name" id="client_name" title="Client Name" required="">
										<option value="" selected="">Client Name</option>
									</select>
								</div>
							</div>
							<div class="col-md-10 form-group form-group1 col-md-offset-1">
								<label class="col-md-4 label-control" for="plant_name">Plant / Unit Name</label>
								<div class="col-md-8">
									<select name="plant_name" id="plant_name" title="Plant Name" required="">
										<option value="" selected="">Plant Name</option>
									</select>
								</div>
							</div>
							<div class="col-md-10 form-group form-group1 col-md-offset-1">
								<label class="col-md-4 label-control" for="plant_name">Order Copy</label>
								<div class="col-md-8"> <input type="file" name="order_copy"> </div>
							</div>
							<div class="col-md-10 form-group form-group1 col-md-offset-1">
								<label class="col-md-4 label-control">New Client Drawings (if any)</label>
								<input type="hidden" id="tot_new_dr" name="tot_new_dr" value="1">
								<div class="client_drawings_wrapper col-md-8 no_padding">
									<div class="client_drawing_div">
										<div class="col-md-6 no_rpadding">
											<input type="file" name="client_drawing_1">
										</div>
										<div class="col-md-6 no_lpadding">
											<input type="text" name="client_drawing_number_1" placeholder="Drawing No." class="drawing_numbers">
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-10 form-group form-group1 col-md-offset-1">
								<div class="col-md-12 text-right">
									<button class="btn" type="button" id="remove_clone">Remove</button>
									<button class="btn" type="button" id="clone_add_more_dr">Add More</button>
								</div>
							</div>
							<div class="col-md-12 form-group form-group1" id="products_wrapper">
								<div class="col-md-12">
									<table id="table_product_details" class="table table-bordered table-hover mini_table">
				        		<caption>Product Details</caption>
				        		<thead>
				        			<tr>
				        				<th>Select</th>
				        				<th>Sr. No.</th>
				        				<th>Section</th>
				        				<th>Make</th>
				        				<th>Description</th>
				        				<th>Drawing Number</th>
				        				<th>Item Code</th>
				        				<th>Quantity</th>
				        				<th>Rate/Piece</th>
				        				<th>Discount</th>
				        				<th>Due Dt.</th>
				        				<th>Delivery Dt.</th>
				        			</tr>
				        		</thead>
				        		<tfoot>
				        			<th colspan="7" class="text-center">Total Order Value</th>
				        			<th colspan="2">
				        				<input type="text" name="order_value" id="order_value" value="-" title="Total Order Value" readonly required>
				        			</th>
				        			<th></th>
				        			<th colspan="2"></th>
				        		</tfoot>
				        	</table>
			        	</div>
		        	</div>
							<div class="col-md-10 form-group form-group1 col-md-offset-1 text-center">
								<button class="btn btn-primary" id="add_order_btn">Add Order</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('include_js')
	<script src="{{ URL::asset('js/select2.full.min.js') }}"></script>
@endsection

@section('functions_js')
	function getProdsLayoutForQtn(quotation_no, data_arr){
		console.log(data_arr);
		var curr_prod_c = $('.order_product').length;
		var prod_count = curr_prod_c + 1;
		var prod_c_e = curr_prod_c + data_arr.products.length;
		var result = '<tbody id="'+quotation_no+'">';
		data_arr.products.forEach((e,f)=>{
			var prod_item_code = e.product_item_code;
			var drawing_html = "";
			e.drawing_no.split(",").forEach((v,k)=>{
				drawing_html += '<input type="text" name="order_product_drawing-'+prod_item_code+(k+1)+'" id="order_product_drawing-'+prod_item_code+'__'+(k+1)+'" class="product_drawing" value="'+v+'" readonly disabled>';
			});
			drawing_html += '<select class="form-control new_drawings" name="order_product_drawing_new-'+prod_item_code+'[]" multiple disabled></select>';
		 	result += '<tr class="product_tr"><td class="text-center"><input type="checkbox" name="order_products[]" id="'+quotation_no+'-prodc-'+(f+1)+'" value="'+prod_item_code+'" class="custom_checkbox order_product '+quotation_no+'"><label for="'+quotation_no+'-prodc-'+(f+1)+'"></label></td><td class="prod_count">'+prod_count+'</td><td><input type="text" name="order_product_section-'+prod_item_code+'" id="order_product_section-'+prod_item_code+'" value="" placeholder="Section" title="Section" disabled></td><td><input type="text" name="order_product_make-'+prod_item_code+'" id="order_product_make-'+prod_item_code+'" value="" placeholder="Make" title="Make" disabled></td><td><input type="text" name="order_product_name-'+prod_item_code+'" id="order_product_name-'+prod_item_code+'" class="product_product_name" value="'+e.product_name+'" readonly disabled></td><td>'+drawing_html+'</td><td><p class="form-control-static">'+prod_item_code+'</p></td><td><input type="text" name="order_product_quantity-'+prod_item_code+'" id="order_product_quantity-'+prod_item_code+'" class="product_quantity" value="'+e.product_quantity+'" placeholder="Quantity" title="Quantity" disabled></td><td><input type="text" name="order_product_rate-'+prod_item_code+'" id="order_product_rate-'+prod_item_code+'" class="product_rate" value="'+e.product_rate+'" placeholder="Rate" title="Rate" disabled></td><td><input type="text" name="order_product_discount-'+prod_item_code+'" id="order_product_discount-'+prod_item_code+'" value="" placeholder="Discount" title="Discount" disabled></td><td><input type="text" name="order_product_due_date-'+prod_item_code+'" id="order_product_due_date-'+prod_item_code+'" class="date_picker" value="" placeholder="Due Date" disabled title="Due Date"></td><td><input type="text" name="order_product_delivery_date-'+prod_item_code+'" id="order_product_delivery_date-'+prod_item_code+'" class="date_picker" value="" placeholder="Delivery Date" disabled title="Delivery Date"></td></tr>';
			prod_count++;
		});
		return result+'</tbody>';
	}

	function calculateTOV(){
		var tov = 0, rate_arr = Array.from($('.product_rate:not(:disabled'));
		Array.from($('.product_quantity:not(:disabled')).forEach((e,i)=>{
			var t_r = rate_arr[i].value;
			t_r = isNaN(Number(t_r)) ? 0 : Number(t_r);
			tov += t_r*Number($(e).val());
		});
		console.log(isNaN(tov));
		$('#order_value').val(isNaN(tov) ? "-" : tov);
	}

	function setOptsAndActivateSelect2(){
		var opts = '';
		$('.drawing_numbers').each((e, v)=>{
			opts += '<option value="'+v.value+'">'+v.value+'</option>';
		});
		$('.new_drawings').val(null).trigger("change");
		$('.new_drawings').html(opts);
		$('.new_drawings').select2({placeholder: "Add new Drawings"});
	}
@endsection

@section('document_ready')
	$('.date_picker').datetimepicker({ format:"YYYY-MM-DD" });

	$(document).on("change", '.custom_checkbox', function(){
		$(this).closest('tr').find(':input:not(#'+this.id+')').prop("disabled", this.checked ? false : true);
		calculateTOV();
	});

	$('#add_order_for').select2({placeholder: "Select Quotation Number(s)"});

	$('#add_order_for').on("select2:select", function(e){
		var qtn_num = e.params.data.id.trim();
	  console.log("selected ", qtn_num);
		$.ajax({
			type: "POST",
			url: "/get_quotation_data",
			data: {quotation_no: qtn_num},
			beforeSend: function(){
				$('body').addClass("is_loading");
			},
			success: function(response){
				response = response.trim();
				console.log("raw response ",response);
				response_arr = JSON.parse(response);
				var prods_layout = getProdsLayoutForQtn(qtn_num, response_arr);
				window.fd = response_arr.client;
				$('#client_name>option[selected]').val(response_arr.client_id).text(response_arr.client_name);
				$('#plant_name>option[selected]').val(response_arr.plant_id).text(response_arr.plant_name);
				$('#table_product_details').append(prods_layout);
				$('.new_drawings').select2({placeholder: "Add new Drawings"});
				$('.date_picker').datetimepicker({ format:"YYYY-MM-DD" });
				$('#products_wrapper').slideDown();
			},
			complete: function(){
				$('body').removeClass("is_loading");
			},
		});
	});

	$('#add_order_for').on("select2:unselect", function(e){
		$("#"+e.params.data.id).remove();
		Array.from($('#table_product_details>tbody>tr>td.prod_count')).forEach((v,k)=>{ $(v).text(k+1); });
		if($('#table_product_details>tbody').length===0){
			$('#client_name>option[selected]').val("").text("Client Name");
			$('#plant_name>option[selected]').val("").text("Plant Name");
		}
	});

	/*$(document).on("select2:select", '.new_drawings', function(e){
		var t_v = e.params.data.id.trim();
		var q_n = $(this).closest('tbody').attr("id");
		if($('input[type="hidden"][name="q_-_'+q_n+'"]').length==0){
			var q_o_q_e = $('<input type="hidden" name="q_-_'+q_n+'">').val(t_v);
			$('#add_enquiry_form').append(q_o_q_e);
		} else {
			var o_v = $('#add_enquiry_form').find('input[type="hidden"][name="q_-_'+q_n+'"]').val();
			if(o_v.indexOf(t_v) < 0){
				$('#add_enquiry_form').find('input[type="hidden"][name="q_-_'+q_n+'"]').val(o_v=="" ? t_v : o_v+"-_-"+t_v);
			}
		}
	});
	
	$(document).on("select2:unselect", '.new_drawings', function(e){
		var t_v = e.params.data.id.trim();
		var q_n = $(this).closest('tbody').attr("id");
		var o_v = $('#add_enquiry_form').find('input[type="hidden"][name="q_-_'+q_n+'"]').val();
		var n_v = o_v.replace(new RegExp(t_v+"-_-|"+t_v+"|-_-"+t_v), "");
		$('#add_enquiry_form').find('input[type="hidden"][name="q_-_'+q_n+'"]').val(n_v);
	});*/

	$(document).on("change", '.product_rate, .product_quantity', function(){ calculateTOV(); });

	$('#add_order_btn').on("click", function(e){
		var inc_f = add_order_for.value===""||$('#order_num').val()===""||$('#order_date').val()===""||$('.order_product:checked').length===0||[0, "-", ""].indexOf($('#order_value').val())>=0;
		if(inc_f){
			swal("Error", "Incomplete Form!", "warning");
			return false;
		} else {
			$('#add_enquiry_form').submit();
		}
	});

	// add more drawings
	$('#clone_add_more_dr').on("click", function(){
		var cc = $('.client_drawing_div:first').clone();
		var tc = $('.client_drawing_div').length+1;
		$('#tot_new_dr').val(tc);
		$(cc).find('input[type="file"]').attr("name", "client_drawing_"+tc).val("");
		$(cc).find('input[type="text"]').attr("name", "client_drawing_number_"+tc).val("");
		//$(cc).append('<button class="remove_clone btn btn-danger"><i class="fa fa-times"></i></button>');
		$('.client_drawings_wrapper').append(cc);
		$('#remove_clone').fadeIn();
	});

	$(document).on("click", '#remove_clone', function(){ $('.client_drawing_div:last').remove(); if($('.client_drawing_div').length===1){$(this).remove();} $('#tot_new_dr').val(parseInt($('#tot_new_dr').val())-1); });

	$(document).on("change", '.drawing_numbers', function(){
		setOptsAndActivateSelect2();
	});
@endsection