<!-- Authenticated Right Dropdown -->
<li class="dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
		{{ Auth::user()->name }} <span class="caret"></span>
	</a>

	<ul class="dropdown-menu" role="menu">
		<!-- Settings -->
		<li class="dropdown-header">Settings</li>

		<li>
			<a href="/settings">
				<i class="fa fa-btn fa-fw fa-cog"></i>Your Settings
			</a>
		</li>

		<!-- Team Settings / List -->
		@if (Laravel\Spark\Spark::usingTeams())
			@include('spark::nav.authed.right.teams')
		@endif

		<!-- Logout -->
		<li class="divider"></li>

		<li>
			<a href="/logout">
				<i class="fa fa-btn fa-fw fa-sign-out"></i>Logout
			</a>
		</li>
	</ul>
</li>
