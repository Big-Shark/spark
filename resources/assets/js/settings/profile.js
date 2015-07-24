var settingsProfileScreen = new Vue({
	el: '#spark-settings-profile-screen',

	ready: function() {
		this.getUser();
	},

	data: {
		user: null,

		updateProfileForm: {
			name: '',
			email: '',
			errors: [],
			updating: false,
			successful: false
		}
	},

	computed: {
		userIsLoaded: function() {
			if (this.user) {
				return true;
			}

			return false;
		}
	},

	methods: {
		getUser: function() {
			this.$http.get('spark/api/user')
				.success(function(user) {
					this.user = user;

					this.updateProfileForm.name = user.name;
					this.updateProfileForm.email = user.email;
				})
				.error(function(errors) {
					//
				});
		},

		updateProfile: function(e) {
			self = this;

			e.preventDefault();

			this.updateProfileForm.errors = [];
			this.updateProfileForm.updating = true;
			this.updateProfileForm.successful = false;

			this.$http.put('user', this.updateProfileForm)
				.success(function() {
					this.updateProfileForm.updating = false;
					this.updateProfileForm.successful = true;
				})
				.error(function(errors) {
					this.updateProfileForm.updating = false;

                    if (typeof errors === 'object') {
                        this.updateProfileForm.errors = _.flatten(_.toArray(errors));
                    } else {
                        this.updateProfileForm.errors.push('Something went wrong. Please try again.');
                    }
				});
		}
	}
});
