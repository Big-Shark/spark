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
