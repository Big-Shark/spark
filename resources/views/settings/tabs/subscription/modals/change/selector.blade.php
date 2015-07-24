<div class="row">
	<!-- Monthly / Available Plans -->
	<div class="col-md-6">
		<div style="margin-bottom: 15px;">
			<span v-if="includesBothPlanIntervals"><strong>Monthly Plans</strong></span>
			<span v-if=" ! includesBothPlanIntervals"><strong>Available Plans</strong></span>
		</div>

		<div v-repeat="plan : defaultPlansExceptCurrent" style="margin-bottom: 10px;">
			@include('spark::settings.tabs.subscription.modals.change.plan')
		</div>
	</div>

	<!-- Yearly Plans -->
	<div class="col-md-6" v-if="includesBothPlanIntervals">
		<div style="margin-bottom: 15px;">
			<strong>Yearly Plans</strong>
		</div>

		<div v-repeat="plan : yearlyPlansExceptCurrent" style="margin-bottom: 10px;">
			@include('spark::settings.tabs.subscription.modals.change.plan')
		</div>
	</div>
</div>
