<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
	<title>@yield('page_title')</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
	<link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}" type='text/css' />
	<!-- Custom Theme files -->
	<link rel="stylesheet" href="{{ URL::asset('css/style.css') }}" type='text/css' />
	<link rel="stylesheet" href="{{ URL::asset('css/font-awesome.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('css/sweetalert.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('css/datatables.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('css/custom.css') }}">
	<link rel="stylesheet" href="http://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
	@yield('include_css')
	<style type="text/css">
		{{-- twitter-typeahead css --}}
		.twitter-typeahead{ width: 100%; }
		.tt-query, /* UPDATE: newer versions use tt-input instead of tt-query */
		.tt-hint {
		    width: 396px;
		    height: 36.38px;
		    padding: 8px 12px;
		    font-size: 24px;
		    line-height: 30px;
		    border: 2px solid #ccc;
		    border-radius: 8px;
		    outline: none;
		}
		.tt-query { /* UPDATE: newer versions use tt-input instead of tt-query */
		    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		}
		.tt-hint {
		    color: #999;
		}
		.tt-menu { /* UPDATE: newer versions use tt-menu instead of tt-dropdown-menu */
		    width: 100%;
		    margin-top: -1px;
		    padding: 8px 0;
		    background-color: #fff;
		    border: 1px solid rgb(233, 233, 233);
		    border-radius: 0;
		}
		.tt-suggestion {
		    padding: 3px 20px;
		    font-size: 14px;
		    line-height: 24px;
		    color: #777;
		}
		.tt-suggestion>strong.tt-highlight{
		    font-weight: 400;
		    color: #111;
		}
		@yield('internal_css')
	</style>
	<script src="{{ URL::asset('js/jquery.min.js') }}"></script>
	<!-- Mainly scripts -->
	<script src="{{ URL::asset('js/jquery.metisMenu.js') }}"></script>
	<script src="{{ URL::asset('js/jquery.slimscroll.min.js') }}"></script>
	<!-- Custom and plugin javascript -->
	<script src="{{ URL::asset('js/custom.js') }}"></script>
	<script src="{{ URL::asset('js/screenfull.js') }}"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
	<script src="http://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
</head>
<body>
	<div id="wrapper">
		@section('app_menu')
		<!--navbar-->
		<nav class="navbar-default navbar-fixed-top" role="navigation">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<h1> <a class="navbar-brand" href="{{ url('/dashboard') }}">AEW</a></h1>
			</div>
			<div class="border-bottom">
				<div class="full-left">
					<section class="full-top">
						<button id="toggle"><i class="fa fa-arrows-alt"></i></button>
					</section>
					<form class=" navbar-left-right">
						<input type="text"  value="Search Anything..." onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Search Anything...';}">
						<input type="submit" value="" class="fa fa-search">
					</form>
					<div class="clearfix"> </div>
				</div>
				<!-- Brand and toggle get grouped for better mobile display -->
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="drop-men" >
					<ul class=" nav_1">
						<li class="dropdown at-drop">
							<a href="#" class="dropdown-toggle dropdown-at " data-toggle="dropdown"><i class="fa fa-2x fa-inbox"></i> <span class="number">4</span></a>
							<ul class="dropdown-menu menu1 " role="menu">
								<li><a href="#">
									<div class="user-new">
										<p>New client added</p>
										<span>40 seconds ago</span>
									</div>
									<div class="user-new-left">
										<i class="fa fa-user-plus"></i>
									</div>
									<div class="clearfix"> </div>
								</a></li>
								<li><a href="#">
									<div class="user-new">
										<p>Received new enquiry</p>
										<span>3 minutes ago</span>
									</div>
									<div class="user-new-left">
										<i class="fa fa-heart"></i>
									</div>
									<div class="clearfix"> </div>
								</a></li>
								<li><a href="#">
									<div class="user-new">
										<p>Quotation due tomorrow</p>
										<span>4 hours ago</span>
									</div>
									<div class="user-new-left">
										<i class="fa fa-times"></i>
									</div>
									<div class="clearfix"> </div>
								</a></li>
								<li><a href="#">
									<div class="user-new">
										<p>Raw Materials module launching soon</p>
										<span>yesterday at 08:30am</span>
									</div>
									<div class="user-new-left">
										<i class="fa fa-info"></i>
									</div>
									<div class="clearfix"> </div>
								</a></li>
								<li><a href="#" class="view">View all messages</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle dropdown-at" data-toggle="dropdown"><span class=" name-caret">Manish<i class="caret"></i></span><img src="{{ URL::asset('images/wo.jpg') }}"></a>
							<ul class="dropdown-menu " role="menu">
								<li><a href="profile.html"><i class="fa fa-user"></i>Edit Profile</a></li>
								<li><a href="inbox.html"><i class="fa fa-envelope"></i>Inbox</a></li>
								<li><a href="calendar.html"><i class="fa fa-calendar"></i>Calender</a></li>
								<li><a href="inbox.html"><i class="fa fa-clipboard"></i>Tasks</a></li>
							</ul>
						</li>
					</ul>
				</div><!-- /.navbar-collapse -->
				<div class="clearfix"></div>
				{{-- END OF HEADER --}}
				<div class="navbar-default sidebar" role="navigation">
					<div class="sidebar-nav navbar-collapse">
						<ul class="nav" id="side-menu">
							<li>
								<a href="{{ url('/dashboard') }}" class=" hvr-bounce-to-right"><i class="fa fa-dashboard nav_icon "></i><span class="nav-label">Analytics Dashboard</span> </a>
							</li>
							<li>
								<a href="{{ url('/manage-clients') }}" class=" hvr-bounce-to-right"><i class="fa fa-users nav_icon "></i><span class="nav-label">Manage Clients</span> </a>
							</li>
							<li>
								<a href="#" class=" hvr-bounce-to-right"><i class="fa fa-indent nav_icon"></i> <span class="nav-label">Enquiry</span><span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li>
										<a href="{{ url('/add-enquiry') }}" class=" hvr-bounce-to-right"><i class="fa fa-plus-square nav_icon "></i><span class="nav-label">Add Enquiry</span> </a>
									</li>
									<li>
										<a href="{{ url('/add-enquiry/submitted') }}" class=" hvr-bounce-to-right"><i class="fa fa-check nav_icon "></i><span class="nav-label">Submitted Enquiries</span> </a>
									</li>
									<li>
										<a href="#" class=" hvr-bounce-to-right"><i class="fa fa-search nav_icon "></i><span class="nav-label">Pending Enquiries</span><span class="fa arrow"></span></a>
										<ul class="nav nav-third-level">
											<li>
												<a href="{{ url('/view-enquiry/pending/with-cost-sheet') }}"><i class="fa fa-toggle-on nav_icon "></i><span class="nav-label">With Cost Sheet</span></a>
											</li>
											<li>
												<a href="{{ url('/view-enquiry/pending/without-cost-sheet') }}"><i class="fa fa-toggle-off nav_icon "></i><span class="nav-label">Without Cost Sheet</span></a>
											</li>
										</ul>
									</li>
									<li>
										<a href="{{ url('/generate-quotation') }}" class=" hvr-bounce-to-right"><i class="fa fa-cogs nav_icon "></i><span class="nav-label">Generate Quotation</span> </a>
									</li>
								</ul>
							</li>
							<li>
								<a href="#" class=" hvr-bounce-to-right"><i class="fa fa-indent nav_icon"></i> <span class="nav-label">Orders</span><span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li>
										<a href="{{ url('/order/create') }}" class=" hvr-bounce-to-right"><i class="fa fa-plus-square nav_icon "></i><span class="nav-label">Add Order</span> </a>
									</li>
									<li>
										<a href="{{ url('/order/pending') }}" class=" hvr-bounce-to-right"><i class="fa fa-cog nav_icon "></i><span class="nav-label">Pending Orders</span> </a>
									</li>
									<li>
										<a href="{{ url('/order/completed') }}" class=" hvr-bounce-to-right"><i class="fa fa-cog nav_icon "></i><span class="nav-label">Completed Orders</span> </a>
									</li>
									<li>
										<a href="{{ url('/order/generate-invoice') }}" class=" hvr-bounce-to-right"><i class="fa fa-cog nav_icon "></i><span class="nav-label">Generate Invoice</span> </a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
				<div class="top_loader"></div>
			</div>
		</nav>
		@show
		<div id="page-wrapper" class="gray-bg dashbard-1">
			<div class="content-main">
				@yield('page_content')
			</div>
			<div class="clearfix"> </div>
			<footer>
				<div class="copy">
					<p> &copy; 2017 AEW. All Rights Reserved | Developed by <a href="mailto:jayesh.6191@gmail.com;nitishthespeedstar@gmail.com;yogz214@gmail.com">JNY Developers</a> | Design by <a href="http://w3layouts.com/" target="_blank">W3layouts</a> </p>
				</div>
			</footer>
		</div>
	</div>
	<script src="{{ URL::asset('js/jquery.nicescroll.js') }}"></script>
	<script src="{{ URL::asset('js/scripts.js') }}"></script>
	<script src="{{ URL::asset('js/bootstrap.min.js') }}"> </script>
	<script src="{{ URL::asset('js/sweetalert.min.js') }}"></script>
	{{-- <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script> --}}
	<script src="{{ URL::asset('js/datatables.min.js') }}"></script>
	<script src="{{ URL::asset('js/typeahead.bundle.min.js') }}"></script>
	@include('sweet::alert')
	@yield('include_js')
	<script type="text/javascript">
		@yield('functions_js')
		$(function () {
			$('#supported').text('Supported/allowed: ' + !!screenfull.enabled);
			if (!screenfull.enabled) {
				return false;
			}
			$('#toggle').click(function () {
				screenfull.toggle($('#container')[0]);
			});

			// set csrf token header for every ajax request
			$.ajaxSetup({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
			});
		});
		$(document).ready(function(){
			@yield('document_ready')
		});
		$(window).on('load', function(){
			@yield('window_load')
		});
	</script>
</body>
</html>