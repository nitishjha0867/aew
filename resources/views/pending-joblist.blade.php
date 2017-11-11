@extends('layouts.app')

@section('page_title', 'AEW | Pending Jobs')

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
				Edit Order
			</th>
			<th>
				Complete Order
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
						<button class="btn btn-primary edit_btn" data-jobid="{{$val->job_id}}" data-jobno="{{$val->job_num}}" data-orderno="{{$val->order_num}}">EDIT</button>
					</td>
					<td>
						<button class="btn btn-primary complete_btn" data-jobid="{{$val->job_id}}" data-jobno="{{$val->job_num}}">Complete</button>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  		<div class="modal-dialog" role="document">
	    	<div class="modal-content">
     		 	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        	<h4 class="modal-title text-center" id="myModalLabel">Edit Job</h4>
	      	</div>
			<div class="modal-body">
				<div class="col-md-8 col-md-offset-2">
					<form action="/order/" method="POST" enctype="multipart/form-data">
						{{ csrf_field() }}
						<input type="hidden" name="_method" value="PUT">
						<label class="label-control col-md-4">Status</label>
						<div class="col-md-8 form-group">
							<input class="form-control" type="text" name="job_status" value="">
						</div>

						<label class="label-control col-md-4">Comments</label>
						<div class="col-md-8 form-group">
							<input class="form-control" type="text" name="job_comment" value="">
						</div>

						<label class="label-control col-md-4">Documents</label>
						<div class="col-md-8 form-group">
							<input class="form-control" type="file" name="job_documents[]" multiple>
						</div>
						<!--<div class="addded_documents col-md-8 col-md-offset-2 form-group text-center">	
						</div>-->
						<div class="col-md-4 col-md-offset-4 form-group">
							<input type="submit" class="btn btn-primary" name="Submit" value="submit">
						</div>
						<input type="hidden" name="job_num" value="{{$val->job_num}}">
						<input type="hidden" name="update_type" value="edit_order">
					</form>
				</div>
				<div class="clearfix"></div>
			</div>
	      	<!--<div class="modal-footer">
	        	<button type="button" class="btn btn-primary">Submit</button>
	      	</div>-->
	    </div>
	  	</div>
	</div>


	<!-- Modal -->
	<div class="modal fade" id="completed_order_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  		<div class="modal-dialog" role="document">
	    	<div class="modal-content">
     		 	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        	<h4 class="modal-title text-center" id="myModalLabel">Edit Job</h4>
	      	</div>
			<div class="modal-body">
				<div class="col-md-8 col-md-offset-2">
					<form action="/order/" method="POST" enctype="multipart/form-data">
						{{ csrf_field() }}
						<input type="hidden" name="_method" value="PUT">
						<label class="label-control col-md-6">Date of dispatch</label>
						<div class="col-md-6 form-group">
							<input class="form-control date_picker" name="date_dispatch" value="">
						</div>

						<label class="label-control col-md-6">LR Number</label>
						<div class="col-md-6 form-group">
							<input class="form-control" type="text" name="lr_number" value="">
						</div>

						<label class="label-control col-md-6">LR Copy</label>
						<div class="col-md-6 form-group">
							<input class="form-control" type="file" name="lr_copy">
						</div>
						<!--<div class="addded_documents col-md-8 col-md-offset-2 form-group text-center">	
						</div>-->
						<div class="col-md-4 col-md-offset-4 form-group">
							<input type="submit" class="btn btn-primary" name="Submit" value="submit">
						</div>
						<input type="hidden" name="job_num" value="{{$val->job_num}}">
						<input type="hidden" name="update_type" value="complete_order">
					</form>
				</div>
				<div class="clearfix"></div>
			</div>
	      	<!--<div class="modal-footer">
	        	<button type="button" class="btn btn-primary">Submit</button>
	      	</div>-->
	    </div>
	  	</div>
	</div>

@endsection

@section('document_ready')
	$('.view_table').dataTable();

	$(document.body).on('click', '.edit_btn' ,function(){

		job_num = $(this).attr('data-jobno');
		job_id = $(this).attr('data-jobid');
		order_num = $(this).attr('data-orderno');
		var modal = $('#myModal');
		modal.find('form').attr('action', '/order/'+job_id);
  		modal.find('.modal-title').text('Job No. ' + job_num+' Order No. '+order_num);
  		modal.find('input[name="job_num"]').val(job_num);
		$.ajax({
			type : 'GET',
			url:'/order/'+job_id,
			success : function(data){
				data = JSON.parse(data);
				var status = data.status;
				var comment = data.comment;
				var doc_src = data.other_attachment;
				doc_src = doc_src.split(',');
				modal.find('input[name="job_status"]').val(status);
				modal.find('input[name="job_comment"]').val(comment);
				/*var img_src = "";
				console.log(doc_src);
				$.each(doc_src , function (index, value){
					if(value != "")
					{
						img_src += "<a href='{{ URL::asset('"+value+"')}}' download>Document "+(index+1)+"</a><br/>";
					}
				});
				console.log(img_src);

				modal.find('.addded_documents').html(img_src);*/
			}
		})

		$('#myModal').modal('show');
		
	})

	$(document.body).on('click', '.complete_btn' ,function(){
		job_num = $(this).attr('data-jobno');
		job_id = $(this).attr('data-jobid');
		var modal = $('#completed_order_modal');
		modal.find('form').attr('action', '/order/'+job_id);
		$(modal).modal('show');

	})

	$('.date_picker').datetimepicker({
		format:"YYYY-MM-DD"
	});

	
@endsection