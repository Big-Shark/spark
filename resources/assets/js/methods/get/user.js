module.exports = function () {
	this.$http.get('/spark/api/users/me')
		.success(function(user) {
        	console.log('Spark User Retrieved: Broadcasting...');

			this.$broadcast('userRetrieved', user);
		});
};
