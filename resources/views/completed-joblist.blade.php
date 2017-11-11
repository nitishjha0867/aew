@extends('layouts.app')

@section('page_title', 'AEW | Completed Jobs')

@section('include_css')
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/select2.min.css') }}">
@endsection

@section('internal_css')
	.modal{
   		overflow: visible !important;
	}

	.modal-footer{text-align:center !important;}
@endsection
@php
	$count = 1;
@endphp
@section('page_content')
	<table class="table table-condensed table-hover table-bordered view_table">
		<thead>
			<th>
				Sr. No.
			</th>
			<th>
				Job No.
			</th>
			<th>
				Plant Name
			</th>
			<th>
				Client name
			</th>
			<th>
				Order No.
			</th>
			<th>
				View Order
			</th>
		</thead>
		<tbody>
			@foreach($data as $val)
				<tr>
					<td>
						{{$count++}}
					</td>
					<td>
						{{$val->job_num}}
					</td>
					<td>
						{{$val->plant_name}}
					</td>
					<td>
						{{$val->client_name}}
					</td>
					<td>
						{{$val->order_num}}
					</td>
					<td>
						<button class="btn btn-primary edit_btn">View</button>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

@endsection

@section('document_ready')
	
@endsection