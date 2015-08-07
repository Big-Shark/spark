<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle Navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

			<a class="navbar-brand" href="/" style="padding-top: 19px;">
				<i class="fa fa-btn fa-sun-o"></i>Spark
			</a>
		</div>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				@if (Auth::guest())
					<!-- Guest -->
				@else
					<li><a href="/home">Home</a></li>
				@endif
			</ul>

			<ul class="nav navbar-nav navbar-right">
				@if (Auth::guest())
					<li><a href="/login">Login</a></li>
					<li><a href="/register">Register</a></li>
				@else
					@include('spark::nav.authed.right')
				@endif
			</ul>
		</div>
	</div>
</nav>
