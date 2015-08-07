module.exports = Vue.extend({
    /*
     * Bootstrap the component. Load the initial data.
     */
	ready: function () {
		//
	},


    /*
     * Define the components.
     */
	components: {
		'spark-settings-subscription-screen': require('./dashboard/subscription.js'),
		'spark-settings-teams-screen': require('./dashboard/teams.js')
	}
});
