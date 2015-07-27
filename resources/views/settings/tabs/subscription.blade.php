<!-- Footer Scripts -->
@section('scripts.footer')
	<script>
		{!! file_get_contents(Laravel\Spark\Spark::resource('/js/settings/subscription.js')) !!}
	</script>
@append

<!-- Main Content -->
<div id="spark-settings-subscription-screen" v-cloak>
	<div v-if="userIsLoaded && plansAreLoaded">

		<!-- Current Coupon -->
		@include('spark::settings.tabs.subscription.coupon')

		<!-- Subscribe -->
		@include('spark::settings.tabs.subscription.subscribe')

		<!-- Update Subscription -->
		@include('spark::settings.tabs.subscription.change')

		<!-- Update Credit Card -->
		@include('spark::settings.tabs.subscription.card')

		<!-- Resume Subscription -->
		@include('spark::settings.tabs.subscription.resume')

		<!-- Invoices -->
		@if (count($invoices) > 0)
			@include('spark::settings.tabs.subscription.invoices.vat')

			@include('spark::settings.tabs.subscription.invoices.history')
		@endif

		<!-- Cancel Subscription -->
		@include('spark::settings.tabs.subscription.cancel')
	</div>

	<!-- Change Subscription Modal -->
	@include('spark::settings.tabs.subscription.modals.change')

	<!-- Cancel Subscription Modal -->
	@include('spark::settings.tabs.subscription.modals.cancel')
</div>
