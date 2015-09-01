<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header">

			<!-- Collapsed Hamburger -->
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle Navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

			<!-- Branding Image -->
			<a class="navbar-brand" href="/" style="padding-top: 19px;">
				<i class="fa fa-btn fa-sun-o"></i>Spark
			</a>
		</div>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<!-- Left Side Of Navbar -->
			<ul class="nav navbar-nav">
				<li><a href="/home">Home</a></li>

				@if ( ! Spark::isDisplayingSettingsScreen())
					<!-- Additional User Defined Navbar Items -->
					<!-- Best To Leave Left Side Nav Empty On Settings To Avoid Vue.js Conflicts -->
				@endif
			</ul>

			<!-- Right Side Of Navbar -->
			<ul class="nav navbar-nav navbar-right">
				<!-- Settings Dropdown -->
				@include('spark::nav.dropdown')
			</ul>
		</div>
	</div>
</nav>
