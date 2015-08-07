@extends('spark::app')

<!-- Scripts -->
@section('scripts')
	@include('spark::scripts.common')

	<script src="//cdnjs.cloudflare.com/ajax/libs/URI.js/1.15.2/URI.min.js"></script>
@endsection

<!-- Footer Scripts -->
@section('scripts.footer')
	<script>
		{!! file_get_contents(Laravel\Spark\Spark::resource('/js/common/errors.js')) !!}
		{!! file_get_contents(Laravel\Spark\Spark::resource('/js/auth/registration/simple.js')) !!}
	</script>
@endsection

<!-- Main Content -->
@section('content')
<div id="spark-register-screen" class="container-fluid spark-screen" v-cloak>
	<!-- Invitation -->
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			@include('spark::auth.registration.subscription.invitation')
		</div>
	</div>

	<!-- Basic Information -->
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			@include('spark::auth.registration.simple.basics')
		</div>
	</div>
</div>
@endsection
