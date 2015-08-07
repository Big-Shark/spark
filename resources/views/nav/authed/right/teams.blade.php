<!-- Team Navigation Options -->
<!-- Team Settings -->
@if (Auth::user()->currentTeam)
	<li>
		<a href="/settings/teams/{{ Auth::user()->currentTeam->id }}">
			<i class="fa fa-btn fa-fw fa-cog"></i>Team Settings
		</a>
	</li>
@endif

<li class="divider"></li>

<li class="dropdown-header">Teams</li>

<!-- Create New Team -->
<li>
	<a href="/settings?tab=teams">
		<i class="fa fa-btn fa-fw fa-plus"></i>Create New Team
	</a>
</li>

<!-- Team Listing -->
@foreach (Auth::user()->teams as $team)
	<li>
		<a href="/settings/teams/switch/{{ $team->id }}">
			@if ($team->id === Auth::user()->currentTeam->id)
				<i class="fa fa-btn fa-fw fa-check text-success"></i>{{ $team->name }}
			@else
				<i class="fa fa-btn fa-fw"></i>{{ $team->name }}
			@endif
		</a>
	</li>
@endforeach
