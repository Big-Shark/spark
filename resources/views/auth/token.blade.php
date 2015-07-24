@extends('spark::app')

<!-- Footer Scripts -->
@section('scripts.footer')
	<script>
		$(function() {
			$('.spark-auth-first-field').filter(':visible:first').focus();
		});
	</script>
@endsection

<!-- Main Content -->
@section('content')
<div id="spark-token-screen" class="container-fluid spark-screen">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Two Factor Authentication</div>
				<div class="panel-body">
					@include('spark::common.errors', ['form' => 'default'])

					<form class="form-horizontal spark-form" role="form" method="POST" action="/auth/token">
						{!! csrf_field() !!}

						<div class="form-group">
							<label class="col-md-4 control-label">Verification Code</label>
							<div class="col-md-6">
								<input type="text" class="form-control spark-auth-first-field" name="token">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									<i class="fa fa-btn fa-phone"></i>Verify Code
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
