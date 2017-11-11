@extends('layouts.app')

@section('page_title', 'AEW | Manage Enquiries')

@section('include_css')
@endsection

@section('internal_css')
@endsection

@section('head_js')
@endsection

@section('page_content')
	<div class="panel-group" id="accordion">
  <div class="panel">
    <a data-toggle="collapse" data-parent="#accordion" href="#panel_add_client_details">
      <div class="panel-heading">
        <h4 class="panel-title">
          <i class="fa fa-file-text"></i> Generate Quotation
        </h4>
      </div>
    </a>
    <div id="panel_add_client_details" class="panel-collapse collapse in">
      <div class="panel-body">
        <form action="{{ route('generate_quotation') }}" method="POST" id="g_q">
        {{ csrf_field() }}
          <div class="form-group">
            <div class="col-md-6 form-group1">
              <input id="enter_enquiry_number" type="text" name="enquiry_number" class="form-control" placeholder="Enquiry Number" title="Enter enquiry number for which you want to generate quotation" autocomplete="off" value="BWJ1700393" required="">
            </div>
            <div class="clearfix"> </div>
          </div>
          <div class="form-group">
            <div class="col-md-4 form-group1">
              <input id="enter_excise_duty" type="text" name="excise_duty" class="form-control" placeholder="Excise Duty" title="Enter excise duty rate (%)" autocomplete="off" required="">
            </div>
            <div class="col-md-4 form-group1">
              <input id="enter_taxes" type="text" name="taxes" class="form-control" placeholder="Taxes" title="Enter tax rate and additional details, if any" autocomplete="off" required="">
            </div>
            <div class="col-md-4 form-group1">
              <input id="enter_delivery_details" type="text" name="delivery_details" class="form-control" placeholder="Delivery details" title="Enter delivery details" autocomplete="off" required="">
            </div>
            <div class="clearfix"> </div>
          </div>
          <div class="form-group">
            <div class="col-md-4 form-group1">
              <input id="enter_payment_details" type="text" name="payment_details" class="form-control" placeholder="Payment Details" title="Enter tax details" autocomplete="off" required="">
            </div>
            <div class="col-md-4 form-group1">
              <input id="enter_packing_details" type="text" name="packing_details" class="form-control" placeholder="Packing Details" title="Enter packing details" autocomplete="off" required="">
            </div>
            <div class="clearfix"> </div>
          </div>
          <div class="col-md-12 form-group">
            <button type="submit" class="btn btn-submit" id="generate_quotation">Generate Quotation</button>
            <button type="reset" class="btn btn-default">Reset</button>
          </div>
          <div class="clearfix"> </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('include_js')
@endsection

@section('functions_js')
@endsection

@section('document_ready')
@endsection