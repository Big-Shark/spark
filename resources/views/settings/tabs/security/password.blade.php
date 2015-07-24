<div class="panel panel-default">
	<div class="panel-heading">Update Password</div>

	<div class="panel-body">
		@include('spark::common.errors', ['form' => 'updatePassword'])

		@if (session('updatePasswordSuccessful'))
			<div class="alert alert-success">
				<strong>Great!</strong> Your password was successfully updated.
			</div>
		@endif

		<form method="POST" action="/settings/user/password" class="form-horizontal spark-form" role="form">
			{!! csrf_field() !!}
			<input type="hidden" name="_method" value="PUT">

			<div class="form-group">
				<label class="col-md-3 control-label">Current Password</label>
				<div class="col-md-6">
					<input type="password" class="form-control" name="old_password">
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">New Password</label>
				<div class="col-md-6">
					<input type="password" class="form-control" name="password">
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">Confirm Password</label>
				<div class="col-md-6">
					<input type="password" class="form-control" name="password_confirmation">
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
