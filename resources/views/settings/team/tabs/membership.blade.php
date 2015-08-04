@if (Auth::user()->ownsTeam($team))
<div class="panel panel-default">
	<div class="panel-heading">Send Invitation</div>

	<div class="panel-body">
		<form method="POST" action="/settings/teams/{{ $team->id }}/invitations" class="form-horizontal" role="form">
			{!! csrf_field() !!}

			<div class="form-group">
				<label class="col-md-3 control-label">E-Mail Address</label>
				<div class="col-md-6">
					<input type="email" class="form-control" name="email" value="{{ old('email') }}">
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-6 col-md-offset-3">
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-btn fa-envelope"></i> Send
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endif

<div class="panel panel-default">
	<div class="panel-heading">Team Members</div>

	<div class="panel-body">
		<table class="table table-responsive">
			<thead>
				<tr>
					<th>Name</th>
					<th>Owner</th>
					<th>Status</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			@foreach ($team->users as $user)
				<tr>
					<td style="padding-top: 14px;">{{ $user->name }}</td>

					<td style="padding-top: 14px;">
						@if ($user->ownsTeam($team))
							<i class="fa fa-check"></i>
						@else
							&nbsp;
						@endif
					</td>

					<td style="padding-top: 14px;">
						Invitation Accepted
					</td>

					<td>
						<button class="btn btn-primary">
							@if (Auth::user()->ownsTeam($team))
								<i class="fa fa-btn fa-edit"></i>Edit
							@endif
						</button>
					</td>

					<td>
						<button class="btn btn-danger">
							@if (Auth::user()->ownsTeam($team))
								<i class="fa fa-btn fa-times"></i>Remove
							@endif
						</button>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>

@if (! Auth::user()->ownsTeam($team))
<div class="panel panel-default">
	<div class="panel-heading">Leave Team</div>

	<div class="panel-body">
		<button class="btn btn-warning">
			<i class="fa fa-btn fa-sign-out"></i>Leave Team
		</button>
	</div>
</div>
@endif
