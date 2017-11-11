@extends('layouts.app')

@section('page_title', 'AEW | Similar Enquiries')

@section('include_css')
@endsection

@section('internal_css')
@endsection

@section('head_js')
@endsection

@section('page_content')
	@php
		// dd($sim_enq_data);
		$sim_enq_str = $ic_dn_str = "";
		$sim_enq = json_decode($sim_enq_data, true);
		if(count($sim_enq)===0){
			$sim_enq_str = "<b>No similar enquiries found</b> for <u>".$this_enq_num."</u>";
		} else {
			if(isset($sim_enq['ic'])){
				$sim_enq_arr = $sim_enq['ic'];
				$ic_dn_str = "Item Codes";
			} else if(isset($sim_enq['dn'])){
				$sim_enq_arr = $sim_enq['dn'];
				$ic_dn_str = "Drawing Numbers";
			}
			$sim_enq_str = "Enquiries having similar <b>".$ic_dn_str."</b> for <u>".$this_enq_num."</u>";
		}
		$index = 0;
		// http://aew.dev/similar-enquiries/WWWWWWWWWWWWW-NBNM-99NBNM-99NBNM-99-SIM-2017
	@endphp
	<div class="panel-group" id="accordion">
	    <div class="panel">
	      <a data-toggle="collapse" data-parent="#accordion" href="#">
	        <div class="panel-heading">
	          <h4 class="panel-title">
	            <i class="fa fa-search"></i>{!! $sim_enq_str !!}
	          </h4>
	        </div>
	      </a>
				@if(count($sim_enq)!==0)
	      <div id="panel_add_client_details" class="panel-collapse collapse in">
	        <div class="panel-body">
	        	<table class="table table-condensed table-hover border_top view_table">
							<thead>
								<tr>
									<th>Sr. No.</th>
									<th>Enquiry No.</th>
									<th>Enquiry Submitted</th>
									<th>Quotation No.</th>
								</tr>
							</thead>
							<tbody>
								@foreach($sim_enq_arr as $enq)
									<tr>
									<td>{{ ++$index }}</td>
									<td><a href="/add-enquiry/{{ $enq['sr_no'] }}">{{ $enq['enquiry_no'] }}</a></td>
									<td>{{ $enq['enquiry_submitted']===1?"Yes":"No" }}</td>
									<td>{{ $enq['quotation_no'] }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
		   </div>
			@endif
		</div>
	</div>
@endsection

@section('include_js')
@endsection

@section('functions_js')
@endsection

@section('document_ready')
@endsection