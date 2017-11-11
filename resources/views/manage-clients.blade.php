@extends('layouts.app')

@section('page_title', 'AEW | Manage Clients')

@section('include_css')
@endsection

@section('internal_css')
  tr.cloneable_cp>td.delete_clone_btn_wrapper {
    width: 23px;
    position: relative;
  }
  button.delete_cloned_cp, button.remove_cloned_cp  {
    position: absolute;
    top: 8px;
    height: 34px;
    background: #ddd;
    border: none;
    font-size: 14px;
  }
  table.cloneable_cp {
    position: relative;
    margin-bottom: 15px;
  }
  .clients_result_wrapper>table, .address_wrapper_view{ display: none; }
@endsection

@section('head_js')
@endsection

@section('page_content')

<div class="panel-group" id="accordion">
  <div class="panel">
    <a data-toggle="collapse" data-parent="#accordion" href="#panel_add_client_details">
      <div class="panel-heading">
        <h4 class="panel-title">
          <i class="fa fa-user-plus"></i> Add / <span data-toggle="tooltip" data-placement="right" title="You can only update Contact Persons' Details!<br>Check info on right for more details."">Update Client Details </span><div class="pull-right" data-toggle="tooltip" data-placement="left" title="You can only update Contact Persons' Details. Changing client name, plant name, or plant address will create an another plant / client with those details!">Info <i class="fa fa-exclamation-circle"></i></div>
        </h4>
      </div>
    </a>
    <div id="panel_add_client_details" class="panel-collapse collapse in">
      <div class="panel-body">
        <form action="{{ route('manage-clients.plant', ['store'=>'contactperson', 'action'=>'update']) }}" method="POST" id="a_u_c_d_f">
        {{ csrf_field() }}
          <div class="form-group">
            <div class="col-md-6 form-group1">
              <input id="enter_client_name" type="text" name="client_name" class="form-control" placeholder="Client Name" title="Enter an existing client or a new one" autocomplete="off" data-ajax="client1" required="">
            </div>
            <div class="col-md-6 form-group1 form-last">
              <input id="enter_plant_name" type="text" name="plant_name" class="form-control" placeholder="Plant Name" title="Enter a new plant or a new one for this client" autocomplete="off" data-ajax="plant1" required="">
            </div>
            <div class="clearfix"> </div>
          </div>
          <div class="address_wrapper_select">
            <div class="col-md-6 form-group1">
              <textarea type="text" name="plant_address" class="form-control" placeholder="Plant Address" required=""></textarea>
            </div>
            <div class="col-md-6 form-group1">
              <div class="form-group">
                <select id="select_plant_state" data-ajax="state1" name="plant_state" class="form-control">
                  <option value="" disabled selected>Select State</option>
                  @foreach($all_states as $state)
                    <option value="{{ $state }}">{{ $state }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <select id="select_plant_city" name="plant_city" class="form-control">
                  <option value="" disabled selected>Select City</option>
                </select>
              </div>
            </div>
            <div class="clearfix"> </div>
          </div>
          <div class="col-md-12">
            <input type="hidden" name="cp_count" id="cp_count" value="1">
            <table class="form-group1 width100 cloned_cp_wrapper">
              <tr class="cloneable_cp" id="clone_1">
                <td colspan="2">
                  <div class="form-group">
                    <input type="hidden" name="contact_id1">
                    <input type="text" name="person_name1" class="form-control" placeholder="Contact Person" required="">
                  </div>
                </td>
                <td>
                  <div class="form-group">
                    <input type="text" name="person_designation1" class="form-control" placeholder="Designation" required="">
                  </div>
                </td>
                <td>
                  <div class="form-group">
                    <input type="text" name="person_phone1" class="form-control" placeholder="Phone" required="">
                  </div>
                </td>
                <td>
                  <div class="form-group">
                    <input type="text" name="person_mobile1" class="form-control" placeholder="Mobile" required="">
                  </div>
                </td>
                <td>
                  <div class="form-group">
                    <input type="text" name="person_email1" class="form-control" placeholder="Email" required="">
                  </div>
                </td>
              </tr>
            </table>
            <div class="clearfix"> </div>
          </div>
          <div class="col-md-12 form-group text-right">
            <button type="button" id="clone_cp_fields" class="btn"><i class="fa fa-plus"></i> Add More</button>
          </div>
          <div class="col-md-12 form-group">
            <button type="submit" class="btn btn-submit" id="add_update_client_details">ADD</button>
            <button type="reset" class="btn btn-default">Reset</button>
          </div>
          <div class="clearfix"> </div>
        </form>
      </div>
    </div>
  </div>
  <div class="panel">
    <a data-toggle="collapse" data-parent="#accordion" href="#panel_add_clients_bulk">
      <div class="panel-heading">
        <h4 class="panel-title">
          <i class="fa fa-upload"></i> Upload Clients CSV
        </h4>
      </div>
    </a>
    <div id="panel_add_clients_bulk" class="panel-collapse collapse">
      <div class="panel-body">
        <form id="clients_csv_form" action="{{ route('manage-clients.plant', ['store'=>'processcsv', 'action'=>'add']) }}" method="POST" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="form-group">
            <div class="col-md-6 form-group1">
              <label class="control-label">CSV File</label>
              <small class="text-danger inline_alert"><i class="fa fa-warning"></i> Avoid uploading the same sheet or files with same data!</small>
              {{-- to: developer@nitish, by:developer@yogesh --}}
              {{-- we may go for multiple files too if feasible --}}
              <input type="file" name="clients_csv" class="form-control" required="">
            </div>
            <div class="col-md-6 form-group1">
              <a href="{{ URL::asset('csvtemplates/client_details_template.csv') }}" download class="btn btn-trans float-right btn_gray">Download CSV Template</a>
            </div>
            <div class="clearfix"> </div>
          </div>
          <div class="col-md-12 form-group">
            <button type="submit" class="btn btn-submit">Upload</button>
            <button type="reset" class="btn btn-default">Reset</button>
          </div>
          <div class="clearfix"> </div>
        </form>
      </div>
    </div>
  </div>
  <div class="panel">
    <a data-toggle="collapse" data-parent="#accordion" href="#panel_view_client_info">
      <div class="panel-heading">
        <h4 class="panel-title">
          <i class="fa fa-search"></i> View Clients Info
        </h4>
      </div>
    </a>
    <div id="panel_view_client_info" class="panel-collapse collapse">
      <div class="panel-body">
        <div class="form-group">
          <div class="col-md-6 form-group1">
            <select id="select_client_name" class="form-control" data-ajax="client2">
              <option value="" disabled selected>Select Client</option>
              @foreach($all_clients as $client_id => $client_name)
                <option value="{{ $client_id }}">{{ $client_name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6 form-group1">
            <select id="select_plant_name" class="form-control" data-ajax="plant2">
              <option value="" disabled selected>Select Plant</option>
            </select>
          </div>
          <div class="clearfix"> </div>
        </div>
        <div class="address_wrapper_view">
          <p>Address:</p>
          <div class="col-md-6 form-group1">
            <p class="form-control static_address static_textarea form-control-static">Address..</p>
          </div>
          <div class="col-md-6 form-group1">
            <div class="form-group">
              <p class="form-control static_state form-control-static">State..</p>
            </div>
            <div class="form-group">
              <p class="form-control static_city form-control-static">City..</p>
            </div>
          </div>
          <div class="clearfix"> </div>
        </div>
        <div class="col-md-12 form-group clients_result_wrapper">
          <table class="table table-condensed table-hover border_top">
            <thead>
              <tr>
                <th class="hidden">Client Name</th>
                <th class="hidden">Plant Name</th>
                <th class="hidden">Plant Address</th>
                <th class="hidden">Plant State</th>
                <th class="hidden">Plant City</th>
                <th>Contact Person</th>
                <th>Designation</th>
                <th>Phone</th>
                <th>Mobile</th>
                <th>Email</th>
              </tr>
            </thead>
            {{-- <tbody>
              <tr>
                <td class="hidden client_name">x131x3213</td>
                <td class="hidden plant_plant">dfhkfd</td>
                <td class="hidden plant_address">fdfdkfdkfjdkfjkfdk jkfjkdf fkdjdfuyu43</td>
                <td class="hidden plant_state">dfuyu43</td>
                <td class="hidden plant_city">f13</td>
                <td class="person_name">Ram</td>
                <td class="person_designation">Site Manager</td>
                <td class="person_phone">28564521</td>
                <td class="person_mobile">9865989568</td>
                <td class="person_email">ram@gmail.com</td>
              </tr>
            </tbody> --}}
          </table>
        </div>
        <div class="clearfix"> </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('include_js')
@endsection

@section('functions_js')
(function($){
  $.fn.extend({
    prepareForDatatable: function(data_array, data_common, prepend_common_data = true, hide_common_data = true, ignore_data_array_keys = ''){
      {{-- console.log(data_array, data_common); --}}
      if(arguments.length<=5){
        var tr = '', tr_id, cdc = hide_common_data ? 'hidden' : '';
        data_array.forEach((o, k)=>{
          var d_a_s = ``, c_d_s = ``;
          {{-- console.log("-----------", typeof ignore_data_array_keys); --}}
          for(d in o){
            if((typeof ignore_data_array_keys === "string" && ignore_data_array_keys !== d) || (typeof ignore_data_array_keys === "object" && ignore_data_array_keys.indexOf(d)<0)){
              console.log("d_a ", d, o[d], ignore_data_array_keys.indexOf(d));
              d_a_s += `<td class="${d}">${o[d]}</td>`;
            }
          }
          for(d in data_common){
            {{-- console.log("c_d ", d, data_common[d], ignore_data_array_keys.indexOf(d)); --}}
            c_d_s += `<td class="${d} ${cdc}">${data_common[d]}</td>`;
          }
          if(typeof ignore_data_array_keys === "string"){
            tr_id = ignore_data_array_keys.split("_")[0]+"_"+(k+1);
          } else if(typeof ignore_data_array_keys === "object"){
            {{-- make this better later --}}
            tr_id = Object.values(ignore_data_array_keys).toString().replace(",", "-")+"_"+(k+1);
          } else {
            tr_id = "tr_"+ignore_data_array_keys+"_"+(k+1);
          }
          tr += prepend_common_data ? `<tr id="${tr_id}">${c_d_s}${d_a_s}</tr>` : `<tr>${d_a_s}${c_d_s}</tr>`;
        });
        {{-- console.log("xxxxx-> ", typeof $(this).find('tbody'), $(this).find('tbody')); --}}
        if($(this).find('tbody').length !== 1){
          $(this).append('<tbody></tbody>');
        }
        {{-- console.log(">>>> ", this, tr); --}}
        $(this).find('tbody').html(tr);
        $(this).slideDown();
      } else {
        console.error("ERROR", "zarurat se jyada parameters mat bhejo!");
      }
      return this;
    }
  });
}(jQuery));

{{-- global functions --}}

function generateClone(cloneable_elem, delete_btn_template, cloneable_elem_wrapper, counter_elem, counter_limit, show_limit_alert = true, show_tooltip = true, swal_res){
  var new_num = $(cloneable_elem).length+1;
  if(new_num-1 <= counter_limit){
    var cloned_copy = $(cloneable_elem+':first').clone().css("display", "none").attr("id", "clone_"+new_num);
    $(cloned_copy).find('.dont_clone').remove();
    cloned_copy.find(':input:not(button)').each((e,v)=>{
      $(v).attr("name", $(v).attr("name").replace(/\d+/, new_num)).val("");
    });
    cloned_copy.find('td:first').attr("colspan", 0);
    cloned_copy.prepend('<td class="delete_clone_btn_wrapper">'+delete_btn_template+'</td>');
    $(cloneable_elem_wrapper).append(cloned_copy);
    $(cloned_copy).fadeIn();
    if(show_tooltip) $('[data-toggle="tooltip"]').tooltip();
    $(counter_elem).val(new_num);
  } else {
    if(show_limit_alert) swal(swal_res.title, swal_res.text, swal_res.text);
  }
}

function generateDeleteCloneBtn(target, action = "delete", show_tooltip = true, fa_icon = "trash"){
  return `<button type="button" data-action="${action}" class="${action}_cloned_cp" title="Remove this ${target}" ${show_tooltip ? 'data-toggle="tooltip"' : ""} data-placement="top"><i class="fa fa-${fa_icon}"></i></button>`;
}

function generateCloneAndSetData(data_array){
  if(typeof data_array === "object"){
    console.log(">>> /// data_array.length is "+data_array.length);
    for(var i=1; i <= data_array.length; i++ ){
      console.log(`...i=${i}`);
      var curr_clones = curr_clones_2 = $('.cloneable_cp').length;
      var clones_diff = curr_clones - data_array.length;
      console.log("...curr_clones "+curr_clones);
      console.log("extra clones "+(curr_clones - data_array.length));
      while(clones_diff > 0){
        console.log('...deleting #clone_'+curr_clones_2);
        $('#clone_'+curr_clones_2).remove();
        clones_diff--; curr_clones_2--;
      }
      if(data_array.length<=1 || i===1){
        console.log("___NOT_cloning");
        $('#clone_1').find('.delete_clone_btn_wrapper').remove();
        $('#clone_1').prepend('<td class="delete_clone_btn_wrapper dont_clone">'+generateDeleteCloneBtn('contact person', 'remove')+'</td>').find('td:nth-child(2)').attr("colspan", 0);
        $('[data-toggle="tooltip"]').tooltip();
      } else if(data_array.length>1 && data_array.length>curr_clones) {
        console.log("___cloning");
        generateClone('.cloneable_cp', generateDeleteCloneBtn('contact person', 'delete'), '.cloned_cp_wrapper', '#cp_count', data_array.length, false, true);
      }
      for(data in data_array[i-1]){
        console.log("each data element >> >> >> ", data);
        $(`input[name="${data}${i}"]`).val(data_array[i-1][data]);
      }
    }
  } else {
    console.error("Execption: Object expected as the 2nd paramter for method 'generateCloneAndSetData', "+(typeof data_array)+" given!");
  }
}

function deleteClone(deletery_elem, cloneable_elem, counter_elem, action = "delete"){
  if(action === "remove"){ $(deletery_elem).closest(cloneable_elem).find(':input').val("");
  console.log(this);
  } else {
    $(deletery_elem).closest(cloneable_elem).fadeOut(function(){
      $(deletery_elem).closest(cloneable_elem).remove();
      var new_num = $(cloneable_elem).length;
      $(counter_elem).val(new_num);
      new_num = 1;
      $(cloneable_elem).each((e, v)=>{
        $(v).attr("id", new_num).find(':input:not(button)').each((e,v)=>{
          $(v).attr("name", $(v).attr("name").replace(/\d+/, new_num));
        });
        new_num++;
      });
    });
  }
}

function ajaxLoad(_this, callbackFn1, callbackFn2 = false){
  var this_val = $(_this).val();
  if(this_val !== "" && this_val !== null){
    var this_typ = $(_this).data("ajax"), ajax_needed = false;
    switch(this_typ){
      case 'client1':
        if(window.clients.local.indexOf(this_val)>=0){
          ajax_needed = true;
        } break;
      case 'plant1':
        if((typeof window.plants !== "undefined")&&(window.plants.local.indexOf(this_val)>=0)){ 
          ajax_needed = true;
          $('[name="plant_address"], [name="plant_state"], [name="plant_city"]').prop("disabled", true);
        } break;
      case 'state1': case 'client2': case 'plant2': ajax_needed = true; break;
    }
    if(ajax_needed){
      $('body').addClass("is_loading");
      console.log("/manage-clients/"+this_typ+"/"+this_val);
      $.get("/manage-clients/"+this_typ+"/"+this_val, function(response){
        callbackFn1(response, this_typ, callbackFn2);
      }, "JSON");
      var form_action = ["state1"].indexOf(this_typ)>=0 ? "add" : "update";
      setFormAction(form_action, '#a_u_c_d_f');
    } else {
      if(this_typ === 'plant1'){
        $('[name="plant_address"], [name="plant_state"], [name="plant_city"]').prop("disabled", false);
      }
      resetClientDetailsFrom(this_typ);
      console.warn("ajax not needed for "+this_typ);
      setFormAction("add", '#a_u_c_d_f');
    }
  }
}

function processDataAndSetOptions(data, type, callbackFn2){
  {{-- console.log(`processDataAndSetOptions ${type} response:`, data); --}}
  switch(type){
    case 'client1':
      if(typeof window.plants !== "undefined"){
        $('input#enter_plant_name').typeahead("destroy").val("");
      }
      if(data.length !== 0){
        window.plants = [];
        for(each in data){  plants.push(data[each].plant_name); }
        // constructs the suggestion engine
        window.plants = new Bloodhound({
          datumTokenizer: Bloodhound.tokenizers.whitespace,
          queryTokenizer: Bloodhound.tokenizers.whitespace,
          local: plants
        });
        $('input#enter_plant_name').typeahead({hint: true, highlight: true, minLength: 1
        }, { name: 'plants', source: plants});
        {{-- window.p_n_t_a = true; --}}
      }
      break;

    case 'client2':
      if(typeof window.c_p_d_t !== "undefined"){
        $('#panel_view_client_info').find('.static_address').text("Address");
        $('#panel_view_client_info').find('.static_state').text("State");
        $('#panel_view_client_info').find('.static_city').text("City");
        $('.clients_result_wrapper .dataTables_wrapper').fadeOut();
      }
      var sel_plants = "";
      for(each in data){
        sel_plants += '<option value="'+data[each].plant_id+'">'+data[each].plant_name+'</option>';
      }
      $('#select_plant_name').html('<option value="" disabled selected>Select Plant</option>'+sel_plants);
      break;

    case 'plant1':
      console.log(data);
      if(data.action === "update"){
        console.log("SELECTED EXISTING PLANT.. UPDATE MODE ON!");
        $('textarea[name="plant_address"]').val(data.plant_details.plant_address);
        $('select[name="plant_state"]').val(data.plant_details.plant_state);
        ajaxLoad('select[name="plant_state"]', processDataAndSetOptions, function(){
          $('select[name="plant_city"]').val(data.plant_details.plant_city);
          generateCloneAndSetData(data.contact_details);
        });
      }
  
      break;

    case 'plant2':
      if(typeof window.c_p_d_t !== "undefined"){ c_p_d_t.destroy(); }
      $('#panel_view_client_info').find('.static_address').text(data.plant_details.plant_address);
      $('#panel_view_client_info').find('.static_state').text(data.plant_details.plant_state);
      $('#panel_view_client_info').find('.static_city').text(data.plant_details.plant_city);
      $('.address_wrapper_view').slideDown();
      data.plant_details.client_name = $('#select_client_name>option:selected').text();
      data.plant_details.plant_name = $('#select_plant_name>option:selected').text();
      window.c_p_d_t = $('.clients_result_wrapper>table')
        .prepareForDatatable(data.contact_details, data.plant_details, true, true, 'contact_id')
        .DataTable({
          dom: '<"dt_opt_top"lf>rti<"dt_opt_bottom"Bp>',
          lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
          buttons: [{ extend: 'csvHtml5', text: "Export in CSV", className: "std_btn" }]
        });
      break;

    case 'state1':
      var city_opts = "";
      for(each in data){
        city_opts += '<option value="'+data[each].city+'">'+data[each].city+'</option>';
      }
      console.log(">> making city dropdown..");
      $('#select_plant_city').html('<option value="" disabled selected>Select City</option>'+city_opts);
      console.log(">> making city dropdown DONE");
      break;

    default: break;
  }
  if(typeof callbackFn2 === "function"){ callbackFn2(); }
  $('body').removeClass("is_loading");
}

function resetClientDetailsFrom(from_what){
  switch(from_what){
    case "client1":
      $('input#enter_plant_name').typeahead("destroy").val("");
    case "plant1":
      $('[name="plant_address"]').val("");
      $('[name="plant_state"]').val("");
      $('[name="plant_city"]').val("");
      $('#clone_1 :input').val("");
      $('.cloned_cp_wrapper>tbody>tr:not(#clone_1)').remove();
      break;
  }
}

var add_act = '{{ route('manage-clients.plant', ['store'=>'contactperson', 'action'=>'add']) }}';
var upd_act = '{{ route('manage-clients.plant', ['store'=>'contactperson', 'action'=>'update']) }}';
function setFormAction(action, form){
  $(form).attr("action", action==="update" ? upd_act : add_act);
  $(form).find('button[type="submit"]').text(action.toUpperCase());
}
@endsection

@section('document_ready')
  $('[data-toggle="tooltip"]').tooltip();
  {{-- clone contact person --}}
  $('#clone_cp_fields').on("click", function(){
    var counter_limit = 100;
    var swal_res = {title:'Too many contacts to manage!', text:`You have already added ${counter_limit} contacts for this plant. You cannot add more than that!`, type:'warning'};
    generateClone('.cloneable_cp', generateDeleteCloneBtn('contact person', 'delete'), '.cloned_cp_wrapper', '#cp_count', counter_limit, true, true, swal_res);
  });

  {{-- remove a cloned contact person --}}
  $(document).on('click', ".delete_cloned_cp, .remove_cloned_cp", function(){
    deleteClone(this, '.cloneable_cp', '#cp_count', $(this).data("action"));
  });

  {{-- typeahead scripts for client and plant name --}}
  @php 
    $clients_name_arr = [];
    foreach($all_clients as $client_id => $client_name){
      $clients_name_arr[] = $client_name;
    }
  @endphp
  window.clients = {!! json_encode($clients_name_arr) !!};
  // constructs the suggestion engine
  window.clients = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: clients
  });

  $('input#enter_client_name').typeahead({ hint: true, highlight: true, minLength: 1
  },{ name: 'clients', source: clients });

  {{-- AJAX EVENT BINDINGS - START --}}
  var ajax_elems = '#select_plant_state, #select_client_name, #select_plant_name';
  $(ajax_elems).on("change", function(){
    ajaxLoad(this, processDataAndSetOptions);
  });
  $('#enter_client_name, #enter_plant_name').on('blur', function(){
    console.log("xxx");
    ajaxLoad(this, processDataAndSetOptions);
  });
  {{-- AJAX EVENT BINDINGS - END --}}

  {{-- FORM CONFIRMATION --}}
  $('#a_u_c_d_f').on("submit", function(e){
    var _t = this;
    e.preventDefault();
    if($(this).attr("action")===upd_act){
      swal({
        title: "Confirmation",
        text: "Are you sure you want to update the details for this plant? You won't be able to recover this later!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Update",
        cancelButtonText: "Cancel",
        closeOnConfirm: false,
        closeOnCancel: false
      }, function(isConfirm){
        if(isConfirm){
          $(_t).off('submit').submit();
        } else {
          swal({
            title: "Action aborted!",
            text: "You aborted the action to update the data. Your data is safe.",
            type: "info"
            }, function(){ location.reload(); });
        }
      });
    } else {
      $(_t).off('submit').submit();
    }
  });
  {{-- FORM CONFIRMATION --}}

@endsection