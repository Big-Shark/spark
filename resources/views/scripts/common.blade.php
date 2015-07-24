@if (Laravel\Spark\Spark::option('vue', true))
	<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/0.12.4/vue.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.1.3/vue-resource.min.js"></script>

	<script>
		Vue.http.headers.common['X-CSRF-TOKEN'] = "{{ csrf_token() }}";
	</script>
@endif

<!-- Form Error Formatting Function -->
<script>
	function setErrorsOnForm(form, errors) {
        if (typeof errors === 'object') {
            form.errors = _.flatten(_.toArray(errors));
        } else {
            form.errors.push('Something went wrong. Please try again.');
        }
	}
</script>
