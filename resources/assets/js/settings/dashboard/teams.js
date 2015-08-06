Vue.component('spark-settings-teams-screen', {
	ready: function () {
		this.getTeams();
		this.getInvitations();
	},


	data: function () {
		return {
			user: null,
			teams: [],
			invitations: [],

			teamToDelete: null,
			deletingTeam: false,

			createTeamForm: {
				name: '',
				errors: [],
				creating: false,
				created: false
			}
		};
	},


	events: {
		userRetrieved: function (user) {
			this.user = user;
		}
	},


	methods: {
		getTeams: function () {
			this.$http.get('/spark/api/teams')
				.success(function (teams) {
					this.teams = teams;
				});
		},


		getInvitations: function () {
			this.$http.get('/spark/api/teams/invitations')
				.success(function (invitations) {
					this.invitations = invitations;
				});
		},


		leaveTeam: function (team) {
			this.teams = _.reject(this.teams, function (t) {
				return t.id == team.id;
			});

			this.$http.delete('/settings/teams/' + team.id + '/membership')
				.success(function () {
					window.location = '/settings?tab=teams';
				});
		},


		confirmTeamDeletion: function (team) {
			this.teamToDelete = team;

			$('#modal-delete-team').modal('show');
		},


		deleteTeam: function () {
			this.deletingTeam = true;

			this.$http.delete('/settings/teams/' + this.teamToDelete.id)
				.success(function (teams) {
					window.location = '/settings?tab=teams';
				});
		},


		acceptInvite: function (invite) {
			this.invitations = _.reject(this.invitations, function (i) {
				return i.id == invite.id;
			});

			this.$http.post('/settings/teams/invitations/' + invite.id + '/accept')
				.success(function (teams) {
					window.location = '/settings?tab=teams';
				});
		},


		rejectInvite: function (invite) {
			this.invitations = _.reject(this.invitations, function (i) {
				return i.id == invite.id;
			});

			this.$http.delete('settings/teams/invitations/' + invite.id);
		},


        userOwns: function (team) {
            if (arguments.length === 2) {
                return arguments[1].id === team.owner_id;
            } else {
                return this.user.id === team.owner_id;
            }
        }
	}
});
