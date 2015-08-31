<!-- Authenticated Right Dropdown -->
<spark-nav-bar-dropdown inline-template>
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" v-if="user">
				@{{ user.name }} <span class="caret"></span>
			</a>

			<ul class="dropdown-menu" role="menu" v-if="user">
				<!-- Settings -->
				<li class="dropdown-header">Settings</li>

				<li>
					<a href="/settings">
						<i class="fa fa-btn fa-fw fa-cog"></i>Your Settings
					</a>
				</li>

				<!-- Team Settings / List -->
				@if (Spark::usingTeams())
					@include('spark::nav.spark.dropdown.teams')
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

</spark-nav-bar-dropdown>
