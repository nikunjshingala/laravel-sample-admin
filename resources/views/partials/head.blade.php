<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>
	@if(trim($__env->yieldContent('pageTitle')))
      	@yield('pageTitle')
    @else
    	{{trans('message.demo_admin')}}
    @endif
</title>

<!-- Fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('global_assets/plugins/fontawesome-free/css/all.min.css')}}">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="{{asset('global_assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">

<link rel="stylesheet" href="{{asset('global_assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('global_assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('global_assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<!-- iCheck -->
<link rel="stylesheet" href="{{asset('global_assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<!-- JQVMap -->
<link rel="stylesheet" href="{{asset('global_assets/plugins/jqvmap/jqvmap.min.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('css/adminlte.min.css')}}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{asset('global_assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{asset('global_assets/plugins/daterangepicker/daterangepicker.css')}}">
<!-- summernote -->
<link rel="stylesheet" href="{{asset('global_assets/plugins/summernote/summernote-bs4.min.css')}}">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" /> -->
<!-- Styles -->
<link rel="stylesheet" href="{{asset('css/custom.css')}}">

<link href="{{ asset('global_assets/plugins/toastr/toastr.min.css')}}"  rel="stylesheet" media="all"></link>
<link href="{{ asset('global_assets/plugins/jquery-multiSelect-master/jquery.multiselect.css')}}"  rel="stylesheet" media="all"></link>
<link href="{{ asset('global_assets/plugins/alertifyjs/css/alertify.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('global_assets/plugins/alertifyjs/css/themes/bootstrap.css')}}" rel="stylesheet" type="text/css">

<style>
    body {
        font-family: 'Nunito', sans-serif;
    }
</style>

@yield('customcss')