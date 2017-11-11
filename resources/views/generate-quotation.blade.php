@extends('layouts.app')

@section('page_title', 'AEW | Generate Quotation')

@section('include_css')
@endsection

@section('internal_css')
  #products_wrapper{display: none;}
  .mb12{margin-bottom: 12px;}
  .tnc_label_w{
    height: 41px;
    padding-top: 12px;
    font-size: 14px;
    text-align: right;
  }
  table.table-xs>thead>tr>th{ text-align: center; }
  table.table-xs>thead>tr>th, table.table-xs>tbody>tr>td{
    padding: 5px 15px !important;
    position: relative;
  }
  table.table-xs>thead, table.table-xs>tfoot{
    background: #f5f5f5;
  }
  .table-xs tr{
    border-top: 1px solid #eee;
  }
  table.table-xs{
    margin-bottom: 25px;
  }
  table.table-xs{
    width: 70%;
    padding: 0 15px;
    border: 1px solid #ddd;
    border-top: 3px double #ddd;
  }
  td span.abs_unit{
    color: #999;
    padding: 14px 25px;
    position: absolute;
    right: 0;
    top: 0;
  }
  .bt_radio{
    width: 100px !important;
    display: inline-block;
    margin-right: 15px;
  }
  .bt_radio{ display: none; }
  .custom_radio{ display:none; }
  .custom_radio+label, label[for="dgst"]{ cursor: pointer; margin-top: 5px; }
  .custom_radio+label:before{
    content: "\f096";
    font-family: FontAwesome;
    font-size: 20px;
    width: 18px;
    display: inline-block;
    margin-right: 5px;
    top: 2px;
    position: relative;
    color: #ccc;
  }
  .custom_radio:checked+label:before{
    content:"\f046";
    color: #111;
    
  }
  #dgst:not(:checked)+label:after{
    content: "&";
    margin: 0 5px;
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
            <i class="fa fa-file-text"></i> Generate Quotation
          </h4>
        </div>
      </a>
      <div id="panel_add_client_details" class="panel-collapse collapse in">
        <div class="panel-body">
          <form action="{{ route('generate_quotation') }}" method="POST" id="g_q">
          {{ csrf_field() }}
            <div class="form-group">
              <div class="col-md-2 tnc_label_w">
                <label for="enter_excise_duty">Enquiry Number</label>
              </div>
              <div class="col-md-6 form-group1">
                <input id="enter_enquiry_number" type="text" name="enquiry_number" class="form-control" placeholder="Enter/Select" title="Enter enquiry number for which you want to generate quotation" autocomplete="off">
              {{-- if SELECT dropdown is required for Enquiry Number --}}
                {{-- <select id="enter_enquiry_number" name="enquiry_number" class="form-control">
                  <option value="" disabled selected>Select Enquiry Number</option>
                  @foreach ($open_enquiries as $en) <option>{{ $en }}</option> @endforeach
                </select> --}}
              </div>
              <div class="clearfix"> </div>
            </div>
            <div id="products_wrapper">
              <div class="col-md-12 mb12">
                <h5><u>Product Details:</u></h5>
              </div>
              <div class="col-md-12">
                <table class="table table-xs table-condensed table-hover border_top">
                  <thead>
                    <tr>
                      <th>Sr. No.</th>
                      <th>Product Name</th>
                      <th>Drawing No.</th>
                      <th>Rate (R) <span title="Type REGRET in case of Regret Rate" data-toggle="tooltip"><i class="fa fa-question-circle"></i></span> </th>
                      <th>Quantity (Q)</th>
                      <th>Amount (R*Q)</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                  <tfoot>
                    <tr class="text-right">
                      <td colspan="5">Total Order Value<input type="hidden" value="0" name="total_order_value"></td>
                      <td>Rs. <b id="total_order_value">0</b></td>
                    </tr>
                  </tfoot>
                </table>
                <div class="form-group">
                  <div class="col-md-2 tnc_label_w">
                    <label for="products_note">Note for above products: </label>
                  </div>
                  <div class="col-md-6 form-group1">
                    <input id="products_note" type="text" name="products_note" class="form-control" placeholder="Enter Note for above products, if any" title="Enter Note for above products, if any" autocomplete="off">
                  </div>
                  <div class="clearfix"> </div>
                </div>
              </div>
            </div>
            <div class="col-md-12 mb12">
              <h5><u>Terms and Conditions:</u></h5>
            </div>
            <div class="form-group">
              <div class="col-md-2 tnc_label_w">
                <label for="enter_excise_duty">Excise Duty</label>
              </div>
              <div class="col-md-6 form-group1">
                <input id="enter_excise_duty" type="text" name="excise_duty" class="form-control" placeholder="Excise Duty %" title="Enter excise duty rate (%)" autocomplete="off" required="">
              </div>
              <div class="clearfix"> </div>
            </div>
            <div class="form-group">
              <div class="col-md-2 tnc_label_w">
                <label for="enter_taxes">Taxes</label>
              </div>
              <div class="col-md-2 form-group1 gst_rows">
                <input type="radio" name="gst" value="igst" class="custom_radio" id="igst"><label for="igst">IGST</label>
                <input id="igst_perc" type="number" name="igst_perc" class="form-control bt_radio" placeholder="IGST %" title="%" autocomplete="off" required="" disabled>
              </div>
              <div class="col-md-4 form-group1 gst_rows">
                <input type="radio" name="gst" value="dgst" class="custom_radio" id="dgst">
                <label for="dgst">CGST </label>
                <input id="cgst_perc" type="number" name="cgst_perc" class="form-control bt_radio" placeholder="CGST %" title="%" autocomplete="off" required="" disabled>
                <label for="dgst">SGST</label>
                <input id="sgst_perc" type="number" name="sgst_perc" class="form-control bt_radio" placeholder="SGST %" title="%" autocomplete="off" required="" disabled>
              </div>
              <div class="clearfix"> </div>
            </div>
            <div class="form-group">
              <div class="col-md-2 tnc_label_w">
                <label for="enter_delivery_details">Delivery</label>
              </div>
              <div class="col-md-6 form-group1">
                <input id="enter_delivery_details" type="text" name="delivery_details" class="form-control" placeholder="Delivery details" title="Enter delivery details" autocomplete="off" required="">
              </div>
              <div class="clearfix"> </div>
            </div>
            <div class="form-group">
              <div class="col-md-2 tnc_label_w">
                <label for="enter_payment_details">Payment</label>
              </div>
              <div class="col-md-6 form-group1">
                <input id="enter_payment_details" type="text" name="payment_details" class="form-control" placeholder="Payment Details (days)" title="Enter tax details" autocomplete="off" required="">
              </div>
              <div class="clearfix"> </div>
            </div>
            <div class="form-group">
              <div class="col-md-2 tnc_label_w">
                <label for="enter_packing_details">Packing</label>
              </div>
              <div class="col-md-6 form-group1">
                <input id="enter_packing_details" type="text" name="packing_details" class="form-control" placeholder="Packing Details" title="Enter packing details" autocomplete="off" required="">
              </div>
              <div class="clearfix"> </div>
            </div>
            <div class="col-md-6 form-group text-center">
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
  function setProductDetails(data_arr){
    var tbody_html = "";
    var t_o_v = 0;
    for(e in data_arr){
      var product_id = Number(data_arr[e].product_id);
      var product_rate = data_arr[e].product_rate=="REGRET"?"REGRET":Number(data_arr[e].product_rate);
      var product_quantity = Number(data_arr[e].product_quantity);
      var product_amount = product_amount_d = "";
      if(product_rate=="REGRET"){
        product_amount_d="-";
        product_amount=0;
      } else {
        product_amount_d=product_amount=product_rate*product_quantity;
      }
      var unit_name = unit_name_og = data_arr[e].unit_name;
      unit_name = product_quantity > 1 ? unit_name+"S" : unit_name ;
      t_o_v += product_amount;
      console.log("t_o_v "+t_o_v, "product_amount "+product_amount);
      tbody_html += `<tr class="product_tr">
                      <td class="text-center">${parseInt(e)+1}</td>
                      <td>${data_arr[e].product_name}<input name="product_${product_id}_name" value="${data_arr[e].product_name}" type="hidden"></td>
                      <td>${data_arr[e].drawing_no}<input name="product_${product_id}_drawing" value="${data_arr[e].drawing_no}" type="hidden"></td>
                      <td><input name="product_${product_id}_rate" value="${product_rate==0?'':product_rate}" class="prod_rate form-control text-center"></td>
                      <td><input name="product_${product_id}_quantity" value="${product_quantity==0?'':product_quantity}" class="prod_qty form-control text-center"> <span class="abs_unit">${unit_name}<input name="product_${product_id}_unitname" value="${unit_name_og}" type="hidden"></span></td>
                      <td class="text-right prod_amt">${product_amount_d}<input name="product_${product_id}_amount" value="${product_amount}" type="hidden" class="prod_amount form-control text-center"></td>
                    </tr>`;
    }
    $('#products_wrapper table>tbody').html(tbody_html);
    $('#products_wrapper table>tfoot #total_order_value').text(t_o_v);
    $('#products_wrapper table>tfoot input[name="total_order_value"]').val(t_o_v);
    $('#products_wrapper').fadeIn();
    $('body').removeClass("is_loading");
  }

  function checkQuotationExistence(_t){
    var t_ = _t;
    var t_v = $(_t).val().trim();
    if(t_v!==""){
      $('body').addClass("is_loading");
      $.ajax({
        type: "POST",
        url: '/check-quotation-existence',
        data: {e_n: t_v},
        success: function(data){
          data = JSON.parse(data);
          s = data['response'];
          if(s=="enquirydoesnotexist"){
            swal('Error!', 'This enquiry does not exist. Please add it first to create its quotation.', 'error');
            $(t_).val('');
            $('#products_wrapper').fadeOut();
          } else {
            if(s=="quotationexists"){
              swal({
                title: "Wait!",
                text: `Quotation for the entered Enquiry No. <code>${t_v}</code> already exists with Quotation No. <code>${data['existing_qtn']}</code>.<br><b>Do you want to create a revised version of Quotation for this Enquiry?</b>`,
                type: "warning", html: true,
                confirmButtonText: "YES",
                cancelButtonText: "NO",
                showCancelButton: true
              }, function(isConfirm){
                if(!isConfirm){
                  $(t_).val('');
                  $('#products_wrapper').fadeOut();
                } else {
                  setProductDetails(data['product_details']);
                }
              });
            } else {
              setProductDetails(data['product_details']);
            }
          }
        },
        complete: function(){
          $('body').removeClass("is_loading");
        }
      });
    }
  }
@endsection

@section('document_ready')
  $('[data-toggle="tooltip"]').tooltip();
  window.enquiries = {!! json_encode($open_enquiries) !!};
  // constructs the suggestion engine
  window.enquiries = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: enquiries
  });

  $('input#enter_enquiry_number').typeahead({ hint: true, highlight: true, minLength: 1
  },{ name: 'enquiries', source: enquiries });

  @if($enq_num!=="")
    $('body').addClass("is_loading");
    $('input#enter_enquiry_number').typeahead('val', "{{ $enq_num }}");
    checkQuotationExistence($('input#enter_enquiry_number'));
  @endif

  $('#g_q').on("submit", function(){
    $('body').addClass("is_loading");
    setTimeout(function(){ $('body').removeClass("is_loading"); }, 3000);
  });

  $('input#enter_enquiry_number').on("blur", function(){
    checkQuotationExistence(this);
  });

  $(document).on("change", '.prod_rate, .prod_qty', function(){
    var c_t_o_v = Number($('#total_order_value').text()),
        c_r = $(this).closest('tr.product_tr').find('input.prod_rate').val(),
        c_a = Number($(this).closest('tr.product_tr').find('td.prod_amt').text()),
        c_q = Number($(this).closest('tr.product_tr').find('input.prod_qty').val()),
        n_a = c_r*c_q,
        n_t_o_v = c_t_o_v;
        c_r = c_r.toUpperCase()==="REGRET" ? 0 : Number(c_r);
    if(n_a>c_a){ n_t_o_v += n_a-c_a; } else if(n_a<c_a){ n_t_o_v -= c_a-n_a; }
    $('#total_order_value').text(n_t_o_v);
    $('input[name="total_order_value"]').val(n_t_o_v);
    $(this).closest('tr.product_tr').find('td.prod_amt').text(n_a);
    $(this).closest('tr.product_tr').find('input.prod_amount').val(n_a);
  });

  $('[name="gst"]').on("change", function(){
    $('.bt_radio').prop("disabled", true).val("").fadeOut();
    $(this).parent().find('.bt_radio').prop("disabled", false).fadeIn();
    var gst_ty = $(this).attr('id');
    if(gst_ty==="igst"){
      $('#igst_perc').focus();
    } else {
      $('#cgst_perc').focus();
    }
  });
@endsection