<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// for testing purpose
Route::get('/test', function(){return view('test');});

// account related routes
Route::get('/', function () {
    return view('login');
});

// routes for CLIENTS module
Route::post('/manage-clients/{store}/{action}', array('as'=>'manage-clients.plant', 'uses'=>'ClientsController@store'))->where(['store'=> 'client|plant|contactperson|processcsv'], ['action'=>'add|update']);
Route::get('/manage-clients/{type}/{value}', array('as'=>'manage-clients.ajax', 'uses'=>'ClientsController@show'))->where('type', 'client\d|plant\d|state\d');
Route::resource('/manage-clients', 'ClientsController');

// routes for DASHBOARD
// since session is not implemented, we are directly returning view without checking the session. in future we may need to implement a controller to handle session based page viewing
Route::post('/dashboard', function(){
	return view('dashboard');
});
Route::get('/dashboard', function(){
	return view('dashboard');
});

// route/s for ENQUIRIES module
Route::get('/similar-enquiries/{similar_enqs_srno}/{enq_num}', 'EnquiryController@showSimilarEnquiries');
Route::get('/generate-quotation', 'EnquiryController@generateQuotationView');
Route::get('/generate-quotation/{enq_num}', 'EnquiryController@generateQuotationView');
Route::post('/generate-quotation', array('as'=>'generate_quotation', 'uses'=>'EnquiryController@generateQuotation'));
Route::post('/add-enquiry', 'EnquiryController@store');
Route::get('/add-enquiry/upload-enquiries-csv', function(){
	return view('upload-enquiries-csv');
});
Route::post('/add-enquiry/process-enquiry-csv', 'EnquiryController@processEnquiryCSV');
Route::post('/check-quotation-existence', 'EnquiryController@checkQuotationExistence');
Route::get('/view-enquiry/{view_enq_type}/{pending_enq_cstsht_status}', 'EnquiryController@show');
Route::resource('/add-enquiry', 'EnquiryController');

// route/s for ORDERS module
// Route::get('/add-order', 'OrderController');
Route::get('/order/generate-invoice', 'OrderController@generateInvoice');
Route::post('/get_quotation_data', 'OrderController@getQuotationData');
Route::get('/order/completed', 'OrderController@completedOrders');
Route::get('/order/pending', 'OrderController@pendingOrders');
Route::resource('/order', 'OrderController');

/*
array:40 [▼
  "_token" => "utqjLosLC9tL8crs6gUsJ1t8LpNiZXzUCaHOpgQy"
  "add_order_for" => array:2 [▼
    0 => "2017-AEW-001"
    1 => "2017-AEW-003-2"
  ]
  "order_num" => "QWE123"
  "order_date" => "2017-09-04"
  "client_name" => "3"
  "plant_name" => "3"
  "tot_new_dr" => "2"
  "client_drawing_number_1" => "NOPE101"
  "client_drawing_number_2" => "YUP101"
  "order_value" => "5837"
  "order_products" => array:3 [▼
    0 => "ABB-001"
    1 => "W-04"
    2 => "W-03"
  ]
  "order_product_section-ABB-001" => "yyyyy"
  "order_product_make-ABB-001" => null
  "order_product_name-ABB-001" => "Steel Rods"
  "order_product_drawing-ABB-0011" => "dddd9999"
  "order_product_drawing_new-ABB-001" => array:1 [▼
    0 => "NOPE101"
  ]
  "order_product_quantity-ABB-001" => "25"
  "order_product_rate-ABB-001" => "125"
  "order_product_discount-ABB-001" => null
  "order_product_due_date-ABB-001" => null
  "order_product_delivery_date-ABB-001" => "2017-09-23"
  "order_product_section-W-04" => null
  "order_product_make-W-04" => "rrrr"
  "order_product_name-W-04" => "Iron Bits"
  "order_product_drawing-W-041" => "DR-2016-03"
  "order_product_drawing_new-W-04" => array:2 [▼
    0 => "NOPE101"
    1 => "YUP101"
  ]
  "order_product_quantity-W-04" => "36"
  "order_product_rate-W-04" => "12"
  "order_product_discount-W-04" => null
  "order_product_due_date-W-04" => "2017-09-18"
  "order_product_delivery_date-W-04" => null
  "order_product_section-W-03" => "wwww"
  "order_product_make-W-03" => "eeee"
  "order_product_name-W-03" => "Iron Rods"
  "order_product_drawing-W-031" => "DR-2016-04"
  "order_product_quantity-W-03" => "152"
  "order_product_rate-W-03" => "15"
  "order_product_discount-W-03" => "21"
  "order_product_due_date-W-03" => null
  "order_product_delivery_date-W-03" => null
]
*/