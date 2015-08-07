module.exports = Vue.extend({
	data: function () {
		return {
			user: null, teams: []
		};
	},

	events: {
		userRetrieved: function (user) {
			this.user = user;
		},

		teamsRetrieved: function (teams) {
			this.teams = teams;
		}
	}
});
