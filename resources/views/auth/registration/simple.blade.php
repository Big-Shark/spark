@extends('spark::app')

<!-- Scripts -->
@section('scripts')
	@include('spark::scripts.common')
@endsection

<!-- Footer Scripts -->
@section('scripts.footer')
	<script>
		{!! file_get_contents(Laravel\Spark\Spark::resource('/js/common/errors.js')) !!}
		{!! file_get_contents(Laravel\Spark\Spark::resource('/js/auth/registration/simple.js')) !!}
	</script>
@endsection

<!-- Main Content -->
@section('content')
<div id="spark-register-screen" class="container-fluid spark-screen" v-cloak>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading">Register</div>
				<div class="panel-body">
					<spark-errors form="@{{ registerForm }}"></spark-errors>

					<form class="form-horizontal spark-form" role="form">
						<div class="form-group">
							<label class="col-md-4 control-label">Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" v-model="registerForm.name">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">E-Mail Address</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" v-model="registerForm.email">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password" v-model="registerForm.password">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Confirm Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation" v-model="registerForm.password_confirmation">
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-6 col-sm-offset-4">
								<div class="checkbox">
									<label>
										<input type="checkbox" v-model="registerForm.terms"> I Accept The <a href="/terms" target="_blank">Terms Of Service</a>
									</label>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-6 col-sm-offset-4">
								<button type="submit" class="btn btn-primary" v-on="click: register" v-attr="disabled: registerForm.registering">
									<span v-if="registerForm.registering">
										<i class="fa fa-btn fa-spinner fa-spin"></i> Registering
									</span>

									<span v-if=" ! registerForm.registering">
										<i class="fa fa-btn fa-check-circle"></i> Register
									</span>
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
