Vue.component('spark-settings-teams-screen', {
    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function () {
        this.getTeams();
        this.getInvitations();
    },


    /*
     * Initial state of the component's data.
     */
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
        /*
         * Handle the "userRetrieved" event.
         */
        userRetrieved: function (user) {
            this.user = user;
        }
    },


    methods: {
        /*
         * Get all of the user's current teams from the API.
         */
        getTeams: function () {
            this.$http.get('/spark/api/teams')
                .success(function (teams) {
                    this.teams = teams;
                });
        },


        /*
         * Get all of the user's pending invitations from the API.
         */
        getInvitations: function () {
            this.$http.get('/spark/api/teams/invitations')
                .success(function (invitations) {
                    this.invitations = invitations;
                });
        },


        /*
         * Leave the team.
         */
        leaveTeam: function (team) {
            this.teams = _.reject(this.teams, function (t) {
                return t.id == team.id;
            });

            this.$http.delete('/settings/teams/' + team.id + '/membership')
                .success(function () {
                    window.location = '/settings?tab=teams';
                });
        },


        /*
         * Confirm that the user really wants to delete the team.
         */
        confirmTeamDeletion: function (team) {
            this.teamToDelete = team;

            $('#modal-delete-team').modal('show');
        },


        /*
         * Delete the given team.
         */
        deleteTeam: function () {
            this.deletingTeam = true;

            this.$http.delete('/settings/teams/' + this.teamToDelete.id)
                .success(function (teams) {
                    window.location = '/settings?tab=teams';
                });
        },


        /*
         * Accept a pending invitation.
         */
        acceptInvite: function (invite) {
            this.invitations = _.reject(this.invitations, function (i) {
                return i.id == invite.id;
            });

            this.$http.post('/settings/teams/invitations/' + invite.id + '/accept')
                .success(function (teams) {
                    window.location = '/settings?tab=teams';
                });
        },


        /*
         * Reject a pending invitation.
         */
        rejectInvite: function (invite) {
            this.invitations = _.reject(this.invitations, function (i) {
                return i.id == invite.id;
            });

            this.$http.delete('settings/teams/invitations/' + invite.id);
        },


        /*
         * Determine if the current user owns the given team.
         */
        userOwns: function (team) {
            if ( ! this.user) {
                return false;
            }

            return this.user.id === team.owner_id;
        }
    }
});
