var teamSettingsScreen = new Vue({
    el: '#spark-team-membership-screen',

    ready: function () {
        this.getUser();
        this.getTeam();
    },


    data: {
        user: null,
    	team: null,

    	sendInviteForm: {
    		errors: [],
    		sending: false
    	}
    },


    computed: {
        everythingIsLoaded: function () {
            return this.user && this.team;
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
    		this.sendInviteForm.sending = true;

    		this.$http.post('/settings/teams/' + TEAM_ID + '/invitations', this.sendInviteForm)
    			.success(function (team) {
    				console.log(team);

    				this.team = team;

    				this.sendInviteForm.sending = false;
    			})
    			.error(function (errors) {
    				this.sendInviteForm.sending = false;
    				setErrorsOnForm(this.sendInviteForm, errors);
    			});
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
