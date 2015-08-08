module.exports = {
    /*
     * Initial state of the component's data.
     */
	data: function () {
		return {
			user: null, teams: []
		};
	},

	events: {
        /**
         * Handle the "userRetrieved" event.
         *
         * This event is broadcast from the root VM.
         */
		userRetrieved: function (user) {
			console.log('Navigation Received User.');

			this.user = user;
		},


        /**
         * Handle the "teamsRetrieved" event.
         *
         * This event is broadcast from the root VM.
         */
		teamsRetrieved: function (teams) {
			console.log('Navigation Received Teams.');

			this.teams = teams;
		}
	}
};
