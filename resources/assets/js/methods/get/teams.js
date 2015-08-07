module.exports = function () {
    this.$http.get('/spark/api/teams')
        .success(function (teams) {
        	console.log('Spark Teams Retrieved: Broadcasting...');

        	this.$broadcast('teamsRetrieved', teams);
        });
};
