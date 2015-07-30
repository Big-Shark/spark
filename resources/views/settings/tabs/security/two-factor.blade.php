<!-- Enable Two-Factor Authentication -->
@if (Laravel\Spark\Spark::supportsTwoFactorAuth() && ! Laravel\Spark\Spark::twoFactorProvider()->isEnabled($user))
	<div class="panel panel-default">
		<div class="panel-heading">Two-Factor Authentication</div>

		<div class="panel-body">
			<div class="alert alert-info">
				To use two factor authentication, you <strong>must</strong> install the
				<a href="https://authy.com" target="_blank">Authy</a> application on your phone.
			</div>

			@include('spark::common.errors', ['form' => 'twoFactor'])

			<form method="POST" action="/settings/user/two-factor" class="form-horizontal" role="form">
				{!! csrf_field() !!}

				<div class="form-group">
					<label class="col-md-3 control-label">Country Code</label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="country_code" value="{{ old('country_code', $user->getAuthCountryCode()) }}" placeholder="1">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-3 control-label">Phone Number</label>
					<div class="col-md-6">
						<input type="text" class="form-control" name="phone_number" value="{{ old('phone_number', $user->getAuthPhoneNumber()) }}" placeholder="555-555-5555">
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-6 col-md-offset-3">
						<button type="submit" class="btn btn-primary">
							<i class="fa fa-btn fa-phone"></i> Enable
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
@endif

<!-- Disable Two-Factor Authentication -->
@if (Laravel\Spark\Spark::supportsTwoFactorAuth() && Laravel\Spark\Spark::twoFactorProvider()->isEnabled($user))
	<div class="panel panel-default">
		<div class="panel-heading">Two-Factor Authentication</div>

		<div class="panel-body">
			<div class="alert alert-info">
				To use two factor authentication, you <strong>must</strong> install the
				<a href="https://authy.com" target="_blank">Authy</a> application on your phone.
			</div>

			@if (session('twoFactorEnabled'))
				<div class="alert alert-success">
					<strong>Nice!</strong> Two-factor authentication is enabled for your account.
				</div>
			@endif

			<form method="POST" action="/settings/user/two-factor" role="form">
				{!! csrf_field() !!}
				<input type="hidden" name="_method" value="DELETE">

				<button type="submit" class="btn btn-danger">
					<i class="fa fa-btn fa-times"></i>Disable
				</button>
			</form>
		</div>
	</div>
@endif
