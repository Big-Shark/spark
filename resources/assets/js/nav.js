module.exports = Vue.extend({
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
			this.user = user;
		},


        /**
         * Handle the "teamsRetrieved" event.
         *
         * This event is broadcast from the root VM.
         */
		teamsRetrieved: function (teams) {
			this.teams = teams;
		}
	}
});
