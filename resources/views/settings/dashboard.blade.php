@extends('spark::app')

<!-- Scripts -->
@section('scripts')
	<script src="https://js.stripe.com/v2/"></script>

	<script>
		STRIPE_KEY = '{{ config('services.stripe.key') }}';
	</script>

	@include('spark::scripts.common')
@append

<!-- Footer Scripts -->
@section('scripts.footer')
	<script>
		{!! file_get_contents(Laravel\Spark\Spark::resource('/js/common/errors.js')) !!}
	</script>
@append

<!-- Main Content -->
@section('content')
<div class="container-fluid spark-screen">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">

			<!-- Tabs -->
			<div class="spark-settings-tabs">
				<ul class="nav nav-tabs" role="tablist">
					@foreach (Laravel\Spark\Spark::settingsTabs()->tabs as $tab)
						<li role="presentation"{!! $tab->key === $activeTab ? ' class="active"' : '' !!}>
							<a href="#{{ $tab->key }}" aria-controls="{{ $tab->key }}" role="tab" data-toggle="tab">
								<i class="fa fa-btn {{ $tab->icon }}"></i>{{ $tab->name }}
							</a>
						</li>
					@endforeach
				</ul>
			</div>

			<!-- Tab Panes -->
			<div class="tab-content">
				@foreach (Laravel\Spark\Spark::settingsTabs()->tabs as $tab)
					<div role="tabpanel" class="tab-pane{{ $tab->key == $activeTab ? ' active' : '' }}" id="{{ $tab->key }}">
						@include($tab->view)
					</div>
				@endforeach
			</div>
		</div>
	</div>
</div>
@endsection
