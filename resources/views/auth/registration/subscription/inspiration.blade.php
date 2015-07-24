<!-- Plan Has Not Been Selected -->
<div v-if="plans.length > 1 && ! registerForm.plan">
	<div class="row spark-subscription-inspiration-heading">
		Thanks for coming on board.
	</div>

	<div class="row spark-subscription-inspiration-subheading">
		Select a plan to get started.
	</div>
</div>

<!-- Plan Is Selected Or There Is Only A Single Plan -->
<div class="row spark-subscription-inspiration-single" v-if="registerForm.plan">
	<span v-if="plans.length == 1">
		Thanks for coming on board.
	</span>

	<span v-if="plans.length > 1">
		Just a few more questions.
	</span>
</div>
