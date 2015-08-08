/*
 * Load the base application dependencies.
 */
require('./common/dependencies');

module.exports = {
	el: '#spark-app',

    /*
     * Bootstrap the application. Load the initial data.
     */
	ready: function () {
        $(function() {
            $('.spark-first-field').filter(':visible:first').focus();
        });

		if (USER_ID) {
			this.getUser();
		}

		if (CURRENT_TEAM_ID) {
			this.getTeams();
		}
	},


    /*
     * Define the components.
     */
	components: {
		'spark-errors': require('./common/errors'),
		'spark-nav-bar': require('./nav'),
		'spark-simple-register-screen': require('./auth/registration/simple'),
		'spark-subscription-register-screen': require('./auth/registration/subscription'),
		'spark-settings-screen': require('./settings/dashboard'),
		'spark-team-settings-screen': require('./settings/team')
	},


	events: {
		updateUser: function () {
			console.log('Received Request To Update User.');

			this.getUser();
		},

		teamsUpdated: function (teams) {
			console.log('Teams Updated: Broadcasting...');

			this.$broadcast('teamsRetrieved', teams);
		}
	},


	methods: {
		/**
		 * Retrieve the user from the API and broadcast it to children.
		 */
		getUser: function () {
			this.$http.get('/spark/api/users/me')
				.success(function(user) {
					console.log('Spark User Retrieved: Broadcasting...');

					this.$broadcast('userRetrieved', user);
				});
		},

        /*
         * Get all of the user's current teams from the API.
         */
        getTeams: function () {
		    this.$http.get('/spark/api/teams')
		        .success(function (teams) {
		        	console.log('Spark Teams Retrieved: Broadcasting...');

					this.$broadcast('teamsRetrieved', teams);
		        });
		}
	}
};
