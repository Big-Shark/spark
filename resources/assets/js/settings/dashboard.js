var sparkDashboardScreen = new Vue({
	el: '#spark-settings-screen',

    /*
     * Bootstrap the component. Load the initial data.
     */
	ready: function () {
		this.getUser();
	},

	methods: {
		/**
		 * Retrieve the user from the API and broadcast it to children.
		 */
		getUser: function () {
			this.$http.get('spark/api/user')
				.success(function(user) {
					this.$broadcast('userRetrieved', user);
				});
		}
	}
});
