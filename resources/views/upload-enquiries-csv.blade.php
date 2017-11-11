@extends('layouts.app')

@section('page_title', 'AEW | Upload Enquiries CSV')

@section('include_css')
@endsection

@section('internal_css')
  .guidelines_wrapper{padding: 25px;}
  .guidelines_wrapper ol{color: #555;}
@endsection

@section('head_js')
@endsection

@section('page_content')
  <div class="panel-group" id="accordion">
    <div class="panel">
      <a data-toggle="collapse" data-parent="#accordion" href="#">
        <div class="panel-heading">
          <h4 class="panel-title">
            <i class="fa fa-user-plus"></i> Upload Enquiries CSV <div class="pull-right" data-toggle="tooltip" data-html="true" data-placement="left" title="Make sure you have used the required CSV Template. If you don't have the template, you can download it by clicking the button 'Download CSV Template' below.<br><b>It is strongly recommended to read the guidelines of uploading Enquiries CSV File before starting the upload.</b>">IMP <i class="fa fa-exclamation-circle"></i></div>
          </h4>
        </div>
      </a>
      <div id="panel_add_enquiries_bulk" class="panel-collapse collapse in">
        <div class="panel-body">
          <form id="enquiries_csv_form" action="process-enquiry-csv" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
              <div class="col-md-6 form-group1">
                <label class="control-label">CSV File</label>
                <small class="text-danger inline_alert"><i class="fa fa-warning"></i> Avoid uploading the same sheet or files with same data!</small>
                <input type="file" name="enquiries_csv" class="form-control" required="">
              </div>
              <div class="col-md-6 form-group1">
                <a href="{{ URL::asset('csvtemplates/enquiries_template.csv') }}" download class="btn btn-trans float-right btn_gray">Download CSV Template</a>
              </div>
              <div class="clearfix"> </div>
            </div>
            <div class="col-md-12 form-group">
              <button type="submit" class="btn btn-submit">Upload</button>
              <button type="reset" class="btn btn-default">Reset</button>
            </div>
            <div class="clearfix"> </div>
          </form>
          <div class="guidelines_wrapper">
            <h5>Guidelines for Uploading Enquiries CSV File:</h5>
            <small>
              <ol>
                <li>Guideline one</li>
                <li>Guideline two</li>
                <li>Guideline three</li>
              </ol>
            </small>
          </div>
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
  $('[data-toggle="tooltip"]').tooltip();
@endsection