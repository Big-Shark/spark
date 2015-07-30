@extends('spark::app')

<!-- Scripts -->
@section('scripts')
	<script src="https://js.stripe.com/v2/"></script>

	<script>
		STRIPE_KEY = '{{ config('services.stripe.key') }}';
	</script>

	@include('spark::scripts.common')

	<script src="//cdnjs.cloudflare.com/ajax/libs/URI.js/1.15.2/URI.min.js"></script>
@endsection

<!-- Footer Scripts -->
@section('scripts.footer')
	<script>
		{!! file_get_contents(Laravel\Spark\Spark::resource('/js/common/errors.js')) !!}
		{!! file_get_contents(Laravel\Spark\Spark::resource('/js/auth/registration/subscription.js')) !!}
	</script>
@endsection

<!-- Main Content -->
@section('content')
<div id="spark-register-screen" class="container spark-screen" v-cloak>
	<!-- Inspiration -->
	<div>
		@include('spark::auth.registration.subscription.inspiration')
	</div>

	<!-- Subscription Plan Selector -->
	<div class="col-md-12" v-if="plans.length > 1 && plansAreLoaded && ! registerForm.plan">
		@include('spark::auth.registration.subscription.plans.selector')
	</div>

	<!-- User Information -->
	<div class="col-md-8 col-md-offset-2" v-if="registerForm.plan || plans.length == 1">
		<!-- The Selected Plan -->
		<div class="row" v-if="plans.length > 1">
			@include('spark::auth.registration.subscription.plans.selected')
		</div>

		<!-- Current Coupon / Discont -->
		<div class="row" v-if="currentCoupon && registerForm.plan && ! freePlanIsSelected">
			@include('spark::auth.registration.subscription.coupon')
		</div>

		<!-- Basic Information -->
		<div class="row">
			@include('spark::auth.registration.subscription.basics')
		</div>

		<!-- Billing Information -->
		<div class="row" v-if=" ! freePlanIsSelected">
			@include('spark::auth.registration.subscription.billing')
		</div>
	</div>
</div>
@endsection
