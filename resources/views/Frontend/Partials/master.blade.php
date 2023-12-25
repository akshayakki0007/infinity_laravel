<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
<meta charset="utf-8" />
<title>@yield('title') | {{ config('app.name') }}</title>
<meta http-equiv="x-ua-compatible" content="ie=edge" />
<meta name="description" content="" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta property="og:title" content="<?php echo $moduleTitle ? $moduleTitle : ''; ?>" />
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('/public/Frontend/images/favicon.ico') }}" />
<link rel="stylesheet" href="{{ asset('/public/Frontend/css/plugins/animate.min.css') }}" />
<link rel="stylesheet" href="{{ asset('/public/Frontend/css/main.css?v='.time()) }}" />
<!-- jQuery 3 -->
<script src="{{ asset('/public/Frontend/js/vendor/jquery-3.6.0.min.js') }}"></script>
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
<link rel="stylesheet" href="{{ asset('/public/Frontend/css/toastr.css') }}" />
<link rel="stylesheet" href="{{ asset('/public/Frontend/css/toastr.min.css') }}" />
<style type="text/css">
    .logo.mb-30.footerImg img {
        width: 50%;
    }
</style>
</head>
@yield('styles')
</head>
<body class="hold-transition skin-blue sidebar-mini" >
	<main class="main">
		@section('_header')
  			@include('Frontend.Partials._header')
  		@show

		@yield('content')
  		
  		@section('_footer')
  			@include('Frontend.Partials._footer')
  		@show
	</main>
	<script type="text/javascript">
		var adminPath = $('meta[name="admin-path"]').attr('content');
	</script>
	@yield('scripts')
	<!-- Vendor JS-->
    <script src="{{ asset('/public/Frontend/js/vendor/modernizr-3.6.0.min.js') }}"></script>
    <script src="{{ asset('/public/Frontend/js/vendor/jquery-migrate-3.3.0.min.js') }}"></script>
    <script src="{{ asset('/public/Frontend/js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/public/Frontend/js/plugins/slick.js') }}"></script>
    <script src="{{ asset('/public/Frontend/js/plugins/jquery.syotimer.min.js') }}"></script>
    <script src="{{ asset('/public/Frontend/js/plugins/waypoints.js') }}"></script>
    <script src="{{ asset('/public/Frontend/js/plugins/wow.js') }}"></script>
    <script src="{{ asset('/public/Frontend/js/plugins/perfect-scrollbar.js') }}"></script>
    <!-- <script src="{{ asset('/public/Frontend/js/plugins/magnific-popup.js') }}"></script> -->
    <script src="{{ asset('/public/Frontend/js/plugins/select2.min.js') }}"></script>
    <!-- <script src="{{ asset('/public/Frontend/js/plugins/counterup.js') }}"></script>
    <script src="{{ asset('/public/Frontend/js/plugins/jquery.countdown.min.js') }}"></script> -->
    <script src="{{ asset('/public/Frontend/js/plugins/images-loaded.js') }}"></script>
    <script src="{{ asset('/public/Frontend/js/plugins/isotope.js') }}"></script>
    <script src="{{ asset('/public/Frontend/js/plugins/scrollup.js') }}"></script>
    <script src="{{ asset('/public/Frontend/js/plugins/jquery.vticker-min.js') }}"></script>
    <script src="{{ asset('/public/Frontend/js/plugins/jquery.theia.sticky.js') }}"></script>
    <!-- <script src="{{ asset('/public/Frontend/js/plugins/jquery.elevatezoom.js') }}"></script> -->
    <!-- Template  JS -->
    <script src="{{ asset('/public/Frontend/js/main.js?v='.time()) }}"></script>
    <!-- <script src="{{ asset('/public/Frontend/js/shop.js?v='.time()) }}"></script> -->
    <script src="{{ asset('/public/Frontend/js/toastr.min.js') }}"></script>
    <script type="text/javascript">
        $(document).on("keypress", "form", function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                $(this).submit();
            }
        });

        //toastr["error"]("My name is Inigo Montoya. You killed my father. Prepare to die!")
    </script>
</body>
</html>