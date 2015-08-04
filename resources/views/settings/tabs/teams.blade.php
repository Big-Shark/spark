<!-- Create Team -->
<div class="panel panel-default">
	<div class="panel-heading">Create Team</div>

	<div class="panel-body">
		@include('spark::common.errors', ['form' => 'createTeam'])

		@if (session('createTeamSuccessful'))
			<div class="alert alert-success">
				<strong>Great!</strong> The team was successfully created.
			</div>
		@endif

		<form method="POST" action="/settings/teams" class="form-horizontal" role="form">
			{!! csrf_field() !!}

			<div class="form-group">
				<label class="col-md-3 control-label">Name</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="name" value="{{ old('name') }}">
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-6 col-md-offset-3">
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-btn fa-users"></i> Create
					</button>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- Current Teams -->
@if (count(Auth::user()->teams) > 0)
<div class="panel panel-default">
	<div class="panel-heading">Current Teams</div>

	<div class="panel-body">
		<table class="table table-responsive">
			<thead>
				<tr>
					<th>Name</th>
					<th>Owner</th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			@foreach (Auth::user()->teams as $team)
				<tr>
					<td style="padding-top: 14px;">{{ $team->name }}</td>

					<td style="padding-top: 14px;">
						@if (Auth::user()->ownsTeam($team))
							You
						@else
							{{ $team->owner->name }}
						@endif
					</td>

					<td>
						@if (Auth::user()->ownsTeam($team))
							<a href="/settings/teams/{{ $team->id }}">
								<button class="btn btn-default">
									<i class="fa fa-btn fa-cog"></i>Settings
								</button>
							</a>
						@endif
					</td>

					<td>
						@if (! Auth::user()->ownsTeam($team))
							<button class="btn btn-warning">
								<i class="fa fa-btn fa-sign-out"></i>Leave
							</button>
						@endif
					</td>

					<td>
						@if (Auth::user()->ownsTeam($team))
							<button class="btn btn-danger">
								<i class="fa fa-btn fa-times"></i>Delete
							</button>
						@endif
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif
