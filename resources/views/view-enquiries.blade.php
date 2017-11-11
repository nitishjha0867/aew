@extends('layouts.app')

@section('page_title', 'AEW | View Enquiries')

@section('include_css')
@endsection

@section('internal_css')

@endsection

@section('page_content')
	<div class="panel-group" id="accordion">
	    <div class="panel">
	      <a data-toggle="collapse" data-parent="#accordion" href="#">
	        <div class="panel-heading">
	          <h4 class="panel-title">
	            <i class="fa fa-search"></i> 
	            {{ ucfirst($show_type) }} Enquiries <b>{{ $cost_sheet_status==="without-cost-sheet"?"(without cost sheet)":($cost_sheet_status!==""?"(with cost sheet)":"") }}</b>
	          </h4>
	        </div>
	      </a>
	      <div id="panel_add_client_details" class="panel-collapse collapse in">
	        <div class="panel-body">
	        	<table class="table table-condensed table-hover border_top view_table">
					@if($show_type == 'submitted')
					<thead>
						<tr>
							<th>Enquiry No.</th>
							<th>Quotation No.</th>
							<th>Client Name</th>
							<th>Plant Name</th>
							<th>Contact person</th>
							<th>Drawing No.</th>
							<th>Item Code</th>
							<th>Product Name</th>
							<th>Enqiry date</th>
							<th>Due date</th>
							<th>Edit</th>
						</tr>
					</thead>
					
					<tbody>
						@for($i = 0; $i<sizeof($enquiry_data); $i++)
							<tr>
								<td><a href="/add-enquiry/{{$enquiry_data[$i]['sr_no']}}">{{$enquiry_data[$i]['enquiry_no']}}</td>
								<!--<td>ytr</td>-->
								<td>{{$enquiry_data[$i]['quotation_no']}}</td>
								<td>{{$enquiry_data[$i]['client_name']}}</td>
								<td>
									{{$enquiry_data[$i]['plant_name']}}
								</td>
								<td>
									{{$enquiry_data[$i]['person_name']}}
								</td>
								<td>
									@php
										$drawing_nums = explode(",", $enquiry_data[$i]['client_drwaing_no']);
									@endphp
									@foreach($drawing_nums as $key=>$value)
										{{ $value }}<br>
									@endforeach
								</td>
								<td>
									@foreach($product_data[$i] as $data)
										{{$data['product_item_code']}}
									@endforeach
								</td>
								<td>
									@foreach($product_data[$i] as $data)
										{{$data['product_name']}}
									@endforeach
								</td>
								<td>
									{{$enquiry_data[$i]['enquiry_date']}}
								</td>
								<td>
									{{$enquiry_data[$i]['due_date']}}
								</td>
								<td><a href="/add-enquiry/{{$enquiry_data[$i]['sr_no']}}/edit"><button class="btn  btn-warning">Edit</button></a>
							</tr>
						@endfor
					</tbody>
					@elseif($show_type == 'pending')
						
						<thead>
						<tr>
							<th>Enquiry No. </th>
							<th>Client Name</th>
							<th>Plant Name</th>
							<th>Contact person</th>
							<th>Drawing No.</th>
							<th>Item Code</th>
							<th>Product Name</th>
							<th>Enqiry date</th>
							<th>Due date</th>
							<th>Edit</th>
							<th class="text-center">Generate Quotation</th>
						</tr>
					</thead>
					
					<tbody>
						@for($i = 0; $i<sizeof($enquiry_data); $i++)
							<tr>
								<td><a href="/add-enquiry/{{$enquiry_data[$i]['sr_no']}}">{{$enquiry_data[$i]['enquiry_no']}}</td>
								<!--<td>ytr</td>-->
								<td>{{$enquiry_data[$i]['client_name']}}</td>
								<td>
									{{$enquiry_data[$i]['plant_name']}}
								</td>
								<td>
									{{$enquiry_data[$i]['person_name']}}
								</td>
								<td>
									@php
										$drawing_nums = explode(",", $enquiry_data[$i]['client_drwaing_no']);
									@endphp
									@foreach($drawing_nums as $key=>$value)
										{{ $value }}<br>
									@endforeach
								</td>
								<td>
									@foreach($product_data[$i] as $data)
										{{$data['product_item_code']}}
									@endforeach
								</td>
								<td>
									@foreach($product_data[$i] as $data)
										{{$data['product_name']}}
									@endforeach
								</td>
								<td>
									{{$enquiry_data[$i]['enquiry_date']}}
								</td>
								<td>
									{{$enquiry_data[$i]['due_date']}}
								</td>
								<td><a href="/add-enquiry/{{$enquiry_data[$i]['sr_no']}}/edit"><button class="btn  btn-warning">Edit</button></a>
								<td class="text-center"><a href="/generate-quotation/{{ $enquiry_data[$i]['enquiry_no'] }}"><button class="btn btn-primary">Generate Now</button></a></td>
							</tr>
						@endfor
					</tbody>
						
					@endif
				</table>
	        </div>
	      </div>
	    </div>
	</div>
<div class="col-md-12">
	
</div>
@endsection

@section('document_ready')
	$('.view_table').dataTable({
        dom: '<"dt_opt_top"lf>rti<"dt_opt_bottom"Bp>',
        {{-- lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]], --}}
        buttons: [{
        	extend: 'csvHtml5',
        	text: "Export in CSV",
        	className: "std_btn",
        	title: 'AEW Enquiries Backup as on {{date("d-m-Y")}}',
            exportOptions: {
                columns: "table th:not(.no_export)"
            }
        }],
		
		"columnDefs": [
		
            {
                "targets": [ 5 ],
                "visible": false,
                "searchable": true
            },
            {
                "targets": [ 6 ],
                "visible": false,
				"searchable": true
            },
			{
                "targets": [ 7 ],
                "visible": false,
				"searchable": true
            }
        ]
    });
	
	$('.update_btn').click(function(){
		var id_num = $(this).attr('data-sr_no');
		var enquiry = $('select[name="enquiry_submitted_'+id_num+'"').val();
		var negotiated_rate = $('input[name="negotiated_rate_'+id_num+'"').val();
		var previous_rate = $('input[name="previous_rate_'+id_num+'"').val();
		
		$.ajax({
			url:id_num,
			type:'PUT',
			data:"enquiry_submitted="+enquiry+"&negotiated_rate="+negotiated_rate+"&previous_rate="+previous_rate,
			success:function(data)
			{
				if(data == 1 || data == "1")
				{
					window.location.reload();
				}
			}
		})
	})
	
@endsection
				