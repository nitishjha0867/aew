@extends('layouts.app')

@section('page_title', 'AEW | Generate Invoice')

@section('include_css')
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/select2.min.css') }}">
@endsection

@section('internal_css')
	.label-control{margin-top:1em;}
	.form-group1{margin:15px 0;}
	.form-inline select.form-control{
		width: 100%;
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
	          <i class="fa fa-plus-square"></i> Generate Invoice
	        </h4>
	      </div>
	    </a>
	    <div id="panel_add_client_details" class="panel-collapse collapse in">
      	<div class="panel-body">
	      	<div class="col-md-10 col-md-offset-1">
						<form action="#" method="POST" id="add_enquiry_form" class="form-inline" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="col-md-10 form-group form-group1 col-md-offset-1">
								<label class="col-md-4 label-control" for="order_num">Order Number</label>
								<div class="col-md-8">
									<select id="order_num" name="order_num" class="form-control">
	                  <option value="" disabled selected>Select</option>
	                  <option value="1">Yes</option><option value="0">No</option>
	                </select>
								</div>
							</div>
							<div class="col-md-10 form-group form-group1 col-md-offset-1 text-center">
								<button class="btn btn-primary add_order_btn">Generate Invoice</button>
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
@endsection

@section('document_ready')
@endsection