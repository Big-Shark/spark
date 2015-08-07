module.exports = Vue.extend({
    /*
     * Bootstrap the component. Load the initial data.
     */
	ready: function () {
		this.getTeam();
	},


    /*
     * Initial state of the component's data.
     */
	data: function () {
		return { team: null };
	},


    /*
     * Define the components.
     */
	components: {
		'spark-team-settings-membership-screen': require('./team/membership')
	},


	events: {
	    /*
	     * Handle the "teamUpdated" event.
	     */
		teamUpdated: function (team) {
			console.log('Spark Team Updated: Broadcasting...');

			this.$broadcast('teamRetrieved', team);

			this.team = team;
		}
	},


	methods: {
	    /*
	     * Get the team from the API.
	     */
		getTeam: function () {
            this.$http.get('/spark/api/teams/' + TEAM_ID)
                .success(function (team) {
                	console.log('Spark Team Retrieved: Broadcasting...');

                	this.team = team;

                	var self = this;

                	Vue.nextTick(function () {
                		self.$broadcast('teamRetrieved', team);
                	})
                });
		}
	}
});
