<!-- Team Navigation Options -->
<!-- Team Settings -->
<li v-if="user.current_team_id">
	<a href="/settings/teams/@{{ user.current_team_id }}">
		<i class="fa fa-btn fa-fw fa-cog"></i>Team Settings
	</a>
</li>

<li class="divider"></li>

<li class="dropdown-header">Teams</li>

<!-- Create New Team -->
<li>
	<a href="/settings?tab=teams">
		<i class="fa fa-btn fa-fw fa-plus"></i>Create New Team
	</a>
</li>

<!-- Team Listing -->
<li v-repeat="team : teams">
	<a href="/settings/teams/switch/@{{ team.id }}">
		<span v-if="team.id == user.current_team_id">
			<i class="fa fa-btn fa-fw fa-check text-success"></i>@{{ team.name }}
		</span>

		<span v-if="team.id !== user.current_team_id">
			<i class="fa fa-btn fa-fw"></i>@{{ team.name }}
		</span>
	</a>
</li>
