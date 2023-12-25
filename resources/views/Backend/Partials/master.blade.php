<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="admin-path" content="{{ url('/admin') }}">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<title>@yield('title') | {{ config('app.name') }}</title>
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{ asset('/public/Backend/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('/public/Backend/bower_components/font-awesome/css/font-awesome.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('/public/Backend/bower_components/Ionicons/css/ionicons.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('/public/Backend/dist/css/AdminLTE.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/Backend/dist/css/skins/_all-skins.min.css') }}">
<!-- Morris chart -->
<link rel="stylesheet" href="{{ asset('/public/Backend/bower_components/morris.js/morris.css') }}">
<!-- jvectormap -->
<link rel="stylesheet" href="{{ asset('/public/Backend/bower_components/jvectormap/jquery-jvectormap.css') }}">
<!-- Date Picker -->
<link rel="stylesheet" href="{{ asset('/public/Backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('/public/Backend/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{ asset('/public/Backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/Backend/bower_components/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/Backend/plugins/timepicker/bootstrap-timepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/Backend/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/Backend/plugins/iCheck/all.css') }}">
<!-- jQuery 3 -->
<script src="{{ asset('/public/Backend/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('/public/Backend/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('/public/Backend/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('/public/Backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<!-- toastr -->
<link rel="stylesheet" type="text/css" href="{{ asset('/public/Backend/plugins/toastr/toastr.min.css') }}">
<!-- sweetalert -->
<link rel="stylesheet" type="text/css" href="{{ asset('/public/Backend/plugins/sweetalert/sweetalert.css') }}">

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<script type="text/javascript">
	$.ajaxSetup({
	  	headers: {
	    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  	}
	});

	var adminPath = $('meta[name="admin-path"]').attr('content');
</script>
@yield('styles')
</head>

<body class="hold-transition skin-blue sidebar-mini" >
	<div class="wrapper">

  		@section('_header')
  			@include('Backend.Partials._header')
  		@show

  		@section('_sidebar')
  			@include('Backend.Partials._sidebar')
		@show

		@yield('content')
  		
  		@section('_footer')
  			@include('Backend.Partials._footer')
  		@show
	
	</div>
<!-- DataTables -->
<script src="{{ asset('/public/Backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/public/Backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<!-- Select2 -->
<script type="text/javascript" src="{{ asset('/public/Backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- InputMask -->
<script type="text/javascript" src="{{ asset('/public/Backend/plugins/input-mask/jquery.inputmask.js') }}"></script>
<script type="text/javascript" src="{{ asset('/public/Backend/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script type="text/javascript" src="{{ asset('/public/Backend/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
<!-- daterangepicker -->
<script type="text/javascript" src="{{ asset('/public/Backend/bower_components/moment/min/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/public/Backend/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<!-- datepicker -->
<script type="text/javascript" src="{{ asset('/public/Backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- bootstrap color picker -->
<script type="text/javascript" src="{{ asset('/public/Backend/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script type="text/javascript" src="{{ asset('/public/Backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<!-- bootstrap time picker -->
<script type="text/javascript" src="{{ asset('/public/Backend/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
<!-- Slimscroll -->
<script type="text/javascript" src="{{ asset('/public/Backend/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- iCheck 1.0.1 -->
<script type="text/javascript" src="{{ asset('/public/Backend/plugins/iCheck/icheck.min.js') }}"></script>
<!-- AdminLTE App -->
<script type="text/javascript" src="{{ asset('/public/Backend/dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<!-- <script type="text/javascript" src="{{ asset('/public/Backend/dist/js/demo.js') }}"></script> -->
<!-- loadingoverlay -->
<script type="text/javascript" src="{{ asset('/public/Backend/plugins/lodingoverlay/loadingoverlay.min.js') }}"></script>
<!-- toastr -->
<script type="text/javascript" src="{{ asset('/public/Backend/plugins/toastr/toastr.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/public/Backend/plugins/toastr/toastr.options.js') }}"></script>
<!-- sweetalert -->
<script type="text/javascript" src="{{ asset('/public/Backend/plugins/sweetalert/sweetalert.js') }}"></script>
<script type="text/javascript">
	//Initialize Select2 Elements
	$('.select2').select2();

	//Date picker
    $('.datepicker').datepicker({
      	autoclose: true,
      	format: 'yyyy-mm-dd'
    });

    setTimeout(function() {
        $(".alert").hide(1500);
    }, 2000);

    $('.float-number').keypress(function(event){
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    var adminPath = $('meta[name="admin-path"]').attr('content');
</script>
@yield('scripts')
</body>
</html>