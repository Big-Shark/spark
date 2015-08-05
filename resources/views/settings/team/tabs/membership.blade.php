<div id="spark-team-membership-screen" v-cloak>
	<div v-if="everythingIsLoaded">
		<!-- Invite New Members -->
		<div class="panel panel-default" v-if="userOwns(team)">
			<div class="panel-heading">Send Invitation</div>

			<div class="panel-body">
				<form method="POST" class="form-horizontal" role="form">
					<spark-errors form="@{{ sendInviteForm }}"></spark-errors>

					<div class="form-group">
						<label class="col-md-3 control-label">E-Mail Address</label>
						<div class="col-md-6">
							<input type="email" class="form-control" name="email" value="{{ old('email') }}">
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-6 col-md-offset-3">
							<button type="submit" class="btn btn-primary" v-on="click: sendInvite" v-attr="disabled: sendInviteForm.sending">
								<span v-if="sendInviteForm.sending">
									<i class="fa fa-btn fa-spinner fa-spin"></i> Sending
								</span>

								<span v-if=" ! sendInviteForm.sending">
									<i class="fa fa-btn fa-envelope"></i> Send
								</span>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>

		<!-- Team Member List -->
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
						<tr v-repeat="teamUser : team.users">
							<td style="padding-top: 14px;">@{{ teamUser.name }}</td>

							<td style="padding-top: 14px;">
								<span v-if="userOwns(team, teamUser)">
									<i class="fa fa-check"></i>
								</span>
							</td>

							<td style="padding-top: 14px;">
								Invitation Accepted
							</td>

							<td>
								<button class="btn btn-primary" v-if="userOwns(team, teamUser)">
									<i class="fa fa-btn fa-edit"></i>Edit
								</button>
							</td>

							<td>
								<button class="btn btn-danger" v-if="userOwns(team, teamUser)">
									<i class="fa fa-btn fa-times"></i>Remove
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<!-- Leave Team -->
		<div class="panel panel-default" v-if=" ! userOwns(team)">
			<div class="panel-heading">Leave Team</div>

			<div class="panel-body">
				<button class="btn btn-warning">
					<i class="fa fa-btn fa-sign-out"></i>Leave Team
				</button>
			</div>
		</div>
	</div>
</div>
