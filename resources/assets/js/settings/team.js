module.exports = Vue.extend({
    /*
     * Bootstrap the component. Load the initial data.
     */
	ready: function () {
		this.getUser();
	},

	components: {
		'spark-team-settings-membership-screen': require('./team/membership')
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
