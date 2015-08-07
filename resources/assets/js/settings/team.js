var teamSettingsScreen = new Vue({
	el: '#spark-team-settings-screen',

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
			this.$http.get('/spark/api/users/me')
				.success(function(user) {
					this.$broadcast('userRetrieved', user);
				});
		}
	}
});
