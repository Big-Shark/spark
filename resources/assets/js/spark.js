/*
 * Load the Spark components.
 */
require('./core/components');

/*
 * Export dependency loader.
 */
module.exports.dependencies = function () {
	require('./core/dependencies');
}

/**
 * Export the Spark application.
 */
module.exports.app = {
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

		this.whenReady();
	},


	events: {
		/**
		 * Handle requests to update the current user from a child component.
		 */
		updateUser: function () {
			this.getUser();
		},

		/**
		 * Receive an updated team list from a child component.
		 */
		teamsUpdated: function (teams) {
			this.$broadcast('teamsRetrieved', teams);
		}
	},


	methods: {
		/**
		 * This method would be overridden by developer.
		 */
		whenReady: function () {
			//
		},


		/**
		 * Retrieve the user from the API and broadcast it to children.
		 */
		getUser: function () {
			this.$http.get('/spark/api/users/me')
				.success(function(user) {
					this.$broadcast('userRetrieved', user);
				});
		},

        /*
         * Get all of the user's current teams from the API.
         */
        getTeams: function () {
		    this.$http.get('/spark/api/teams')
		        .success(function (teams) {
					this.$broadcast('teamsRetrieved', teams);
		        });
		}
	}
};
