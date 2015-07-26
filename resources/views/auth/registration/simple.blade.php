@extends('spark::app')

<!-- Scripts -->
@section('scripts')
	@include('spark::scripts.common')
@endsection

<!-- Footer Scripts -->
@section('scripts.footer')
	<script>
		{!! file_get_contents(Laravel\Spark\Spark::resource('/js/common/errors.js')) !!}
		{!! file_get_contents(Laravel\Spark\Spark::resource('/js/auth/registration/simple.js')) !!}
	</script>

	<script>
		$(function() {
			$('.spark-first-field').filter(':visible:first').focus();
		});
	</script>
@endsection

<!-- Main Content -->
@section('content')
<div id="spark-register-screen" class="container-fluid spark-screen" v-cloak>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			@include('spark::auth.registration.simple.basics')
		</div>
	</div>
</div>
@endsection
