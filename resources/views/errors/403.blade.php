@extends('app')

@section('content')
	<!-- Error title -->
	<div class="text-center content-group error-content">
		@if(session('error_code'))
		<h1 class="error-title">{{ session('error_code') }}</h1>
		<h5> {{ session('error_msg') }} </h5>
		@endif
	</div>
	<!-- /error title -->
@endsection 