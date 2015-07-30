<div id="spark-settings-profile-screen" class="panel panel-default">
	<div class="panel-heading">Update Profile</div>

	<div class="panel-body">
		@include('spark::common.errors', ['form' => 'updateProfile'])

		@if (session('updateProfileSuccessful'))
			<div class="alert alert-success">
				<strong>Great!</strong> Your profile was successfully updated.
			</div>
		@endif

		<form method="POST" action="/settings/user" class="form-horizontal" role="form">
			{!! csrf_field() !!}
			<input type="hidden" name="_method" value="PUT">

			<div class="form-group">
				<label class="col-md-3 control-label">Name</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}">
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">E-Mail Address</label>
				<div class="col-md-6">
					<input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}">
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-6 col-md-offset-3">
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-btn fa-save"></i> Update
					</button>
				</div>
			</div>
		</form>
	</div>
</div>

