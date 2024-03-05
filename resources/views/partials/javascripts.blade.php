@inject('request', 'Illuminate\Http\Request')
<!-- jQuery -->
<script src="{{asset('global_assets/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('global_assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('global_assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<!-- daterangepicker -->
<script src="{{asset('global_assets/plugins/moment/moment.min.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('global_assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<!-- overlayScrollbars -->
<script src="{{asset('global_assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('global_assets/js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
@if(Auth::check())
<script src="{{asset('global_assets/js/demo.js')}}"></script>
<script type="text/javascript" src="{!! url('resource', ['js','commonjs']); !!}"></script>
@endif
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('global_assets/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{ asset('global_assets/plugins/alertifyjs/alertify.js')}}"></script>
<script src="{{ asset('global_assets/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('global_assets/plugins/jquery-validation/additional-methods.min.js')}}"></script>

<!-- alertify custome script js -->
<script type="text/javascript">

    //override defaults
    alertify.defaults.theme.ok = "btn btn-primary";
    alertify.defaults.theme.cancel = "btn btn-danger";
    alertify.set('notifier','position', 'top-right');
    
</script>
<!-- alertify custome script js -->
<script>
    $(document).ready(function() {
        setInterval(function(){ 
			$.ajax({
				url: "{{route('refresh-csrf')}}",
				type: "get",
				data: {_token : $('input[name=_token]').val()},
				dataType: "json",
				success: function(data){
				},complete: function (data) {
					$('input[name=_token]').val(data.responseText);
				}
			});
        }, 500000);
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    });
    @if(session('status') && session('msg'))
    	toastr["{{session('status')}}"]("{{session('msg')}}")
  	@endif
</script>
@yield('javascript')
