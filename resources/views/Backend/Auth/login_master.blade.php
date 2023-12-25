<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{{ config('app.name') }} | {{$moduleTitle}}</title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{ asset('/public/Backend/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('/public/Backend/bower_components/font-awesome/css/font-awesome.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('/public/Backend/bower_components/Ionicons/css/ionicons.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('/public/Backend/dist/css/AdminLTE.min.css') }}">
<!-- iCheck -->
<link rel="stylesheet" href="{{ asset('/public/Backend/plugins/iCheck/square/blue.css') }}">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<link rel="stylesheet" type="text/css" href="{{ asset('/public/Backend/plugins/toastr/toastr.min.css') }}">
</head>
<body class="hold-transition login-page">

	@yield('content')

<script src="{{ asset('/public/Backend/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('/public/Backend/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/public/Backend/plugins/toastr/toastr.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/public/Backend/plugins/toastr/toastr.options.js') }}"></script>
<script type="text/javascript" src="{{ asset('/public/Backend/plugins/lodingoverlay/loadingoverlay.min.js') }}"></script>
@yield('scripts')
</body>
</html>
