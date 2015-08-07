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
		'spark-team-settings-membership-screen': require('./team/membership')
	}
});
