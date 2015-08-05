var teamSettingsScreen = new Vue({
    el: '#spark-team-membership-screen',

    ready: function () {
        this.getUser();
        this.getTeam();
    },


    data: {
        user: null,
    	team: null,
        leavingTeam: false,

    	sendInviteForm: {
            email: '',
    		errors: [],
    		sending: false,
            sent: false
    	}
    },


    computed: {
        everythingIsLoaded: function () {
            return this.user && this.team;
        },


        teamUsersExceptMe: function () {
            self = this;

            return _.reject(this.team.users, function (user) {
                return user.id === self.user.id;
            });
        }
    },


    methods: {
        getUser: function () {
            this.$http.get('/spark/api/user')
                .success(function (user) {
                    this.user = user;
                });
        },


    	getTeam: function () {
    		this.$http.get('/spark/api/teams/' + TEAM_ID)
    			.success(function (team) {
    				this.team = team;
    			});
    	},


    	sendInvite: function (e) {
    		e.preventDefault();

            this.sendInviteForm.errors = [];
            this.sendInviteForm.sent = false;
    		this.sendInviteForm.sending = true;

    		this.$http.post('/settings/teams/' + TEAM_ID + '/invitations', this.sendInviteForm)
    			.success(function (team) {
    				this.team = team;

                    this.sendInviteForm.email = '';
    				this.sendInviteForm.sent = true;
                    this.sendInviteForm.sending = false;
    			})
    			.error(function (errors) {
    				this.sendInviteForm.sending = false;
    				setErrorsOnForm(this.sendInviteForm, errors);
    			});
    	},


        cancelInvite: function (invite) {
            this.team.invitations = _.reject(this.team.invitations, function (i) {
                return i.id === invite.id;
            });

            this.$http.delete('/settings/teams/' + TEAM_ID + '/invitations/' + invite.id);
        },


        editTeamMember: function (teamUser) {
            //
        },


        removeTeamMember: function (teamUser) {
            this.team.users = _.reject(this.team.users, function (u) {
                return u.id == teamUser.id;
            });

            this.$http.delete('/settings/teams/' + TEAM_ID + '/members/' + teamUser.id);
        },


        leaveTeam: function () {
            this.leavingTeam = true;

            this.$http.delete('/settings/teams/' + TEAM_ID + '/membership')
                .success(function () {
                    window.location = '/settings?tab=teams';
                });
        },


        userOwns: function (team) {

            if (arguments.length === 2) {
                console.log(arguments[1].id);
                return arguments[1].id === arguments[0].owner_id;
            } else {
                return this.user.id === arguments[0].owner_id;
            }
        }
    }
});
